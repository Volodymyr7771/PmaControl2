<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \Glial\Synapse\Controller;
use App\Library\Tree as TreeInterval;

class Plugin extends Controller
{

    public function index($param)
    {
        $LOCALJSONFILE = "/data/www/pmacontrol/plugins/plugin.json";
        $PMAPLUGINURL  = "http://localhost/plugins/";
        $JSONURL       = $PMAPLUGINURL."extracted/plugin.json";

        if ((!file_exists($LOCALJSONFILE)) || (filectime($LOCALJSONFILE) < date_timestamp_get(date_create('-1 day')))) {
            if ($file = file_get_contents($JSONURL)) {
                $Array = json_decode($file, true);
                $this->jsontodatabase($file);

                $fp = fopen($LOCALJSONFILE, 'w');
                fwrite($fp, $file);
                fclose($fp);
            }
        }

        $db = $this->di['db']->sql(DB_DEFAULT);

        $Query = "SELECT id, nom, description, auteur, image, fichier, date_installation, md5_zip, version, type_licence, est_actif, maxversion
        FROM plugin_main ";
        $Query .= " INNER JOIN"
            ." (SELECT nom AS tempnom, MAX(version) AS maxversion FROM plugin_main GROUP BY nom)"
            ." AS Temp ON plugin_main.nom = Temp.tempnom";

        if (isset($param[0])) {
            if (strtolower($param[0]) == 'installed') {
                $Query .= " WHERE est_actif = 1";
            }
            if (strtolower($param[0]) == 'toupdate') {
                $Query .= " WHERE plugin_main.version < Temp.maxversion"
                    ." AND plugin_main.est_actif = 1";
            }
        }

        $res = $db->sql_query($Query);

        $plugins = array();
        while ($plugin  = $db->sql_fetch_array($res, MYSQLI_ASSOC)) {
            $plugins[$plugin["nom"]][$plugin["version"]] = $plugin;
        }

        $this->set('data', $plugins);
        $this->set('param', $param);
    }

    public function jsontodatabase($jsonInText)
    {
        $Array = json_decode($jsonInText, true);

        $db = $this->di['db']->sql(DB_DEFAULT);

        foreach ($Array as $key => $line):

            ksort($line);

            foreach ($line as $key2 => $line2):

                $date  = DateTime::createFromFormat('d/m/Y', $line2['CreationDate']);

                $Query = "SELECT * FROM plugin_main WHERE nom = '".addslashes($key)."' AND version = '".addslashes($key2)."'";
                $res = $db->sql_query($Query);

                if ($db->sql_num_rows($res)>0)
                {
                    $Query = "UPDATE plugin_main SET description = '".addslashes($line2['Description'])."', auteur = '".addslashes($line2['Contributor'])."', image = '".addslashes($line2['Picture'])."', fichier = '".addslashes($line2["URL"])."', date_installation = '".$date->format('Y-m-d')."', md5_zip = '".addslashes($line2['MD5'])."', type_licence = '".addslashes($line2['LicenceType'])."' WHERE nom = '".addslashes($key)."' AND version = '".addslashes($key2)."'";
                    $db->sql_query($Query);
                }
                else
                {
                    $Query = "INSERT INTO plugin_main (nom, description, auteur, image, fichier, date_installation, md5_zip, version, type_licence )
SELECT '".addslashes($key)."', '".addslashes($line2['Description'])."','".addslashes($line2['Contributor'])."','".addslashes($line2['Picture'])."','".addslashes($line2["URL"])."','".$date->format('Y-m-d')."','".addslashes($line2['MD5'])."','".addslashes($key2)."','".addslashes($line2['LicenceType'])."'";
                    $db->sql_query($Query);
                }
            endforeach;
        endforeach;
    }

    public function install($param)
    {
        if (!isset($param[0])) {
            Throw new \Exception("No plugin Id provided");
        }

        $LOCALPLUGIN      = "/data/www/pmacontrol/plugins/";
        $LOCALAPPLICATION = "/data/www/pmacontrol/application/";

        $db = $this->di['db']->sql(DB_DEFAULT);

        $Query = "SELECT id, nom, description, auteur, image, fichier, date_installation, md5_zip, version, type_licence, est_actif
        FROM plugin_main WHERE id = ".$param[0];

        $res = $db->sql_query($Query);

        $plugin = array();
        if ($plugin = $db->sql_fetch_array($res, MYSQLI_ASSOC)) {
            //Téléchargement du fichier
            if (!is_dir($LOCALPLUGIN.$plugin["nom"])) {
                //Directory does not exist, so lets create it.
                mkdir($LOCALPLUGIN.$plugin["nom"], 0755, true);
            }

            $handle  = fopen($plugin["fichier"], "r");
            $handle2 = fopen($LOCALPLUGIN.$plugin["nom"]."/".$plugin["version"].".zip", "w");
            if (FALSE === $handle) {
                Throw new \Exception("Echec lors de l'ouverture du flux vers l'URL");
            }

            while (!feof($handle)) {
                fwrite($handle2, fread($handle, 8192));
            }
            fclose($handle);
            fclose($handle2);

            $zip = new ZipArchive;
            $res = $zip->open($LOCALPLUGIN.$plugin["nom"]."/".$plugin["version"].".zip");
            if ($res === TRUE) {
                $zip->extractTo($LOCALPLUGIN."extracted/");
                $zip->close();
            } else {
                Throw new \Exception("Check your ZIP PHP extension or directory right", 99);
            }
        } else {
            Throw new \Exception("Error while loading plugin in database");
        }

        //Copy Plugin ungeneric files
        $ThisPluginDirectory = $LOCALPLUGIN."extracted/".$plugin["nom"]."-".substr($plugin["version"], 1);
        $scanned_directory   = array_diff(scandir($ThisPluginDirectory), array('..', '.', '.gitmodules', 'sql', 'install.php', 'upgrade.php', 'uninstall.php', 'README.md', 'image.jpg'));

        $Return = array();
        $Return[0] = true;

        $source = $ThisPluginDirectory."/";
        $target = $_SERVER["DOCUMENT_ROOT"].WWW_ROOT;


        foreach ($scanned_directory AS $value) {
            if ($Return[0]==true)
            {
                $Return = $this->copyfile($value, $source, $target);
            }
        }

        if ($Return[0] == false) {
            Throw new \Exception("Error while copying plugin files : ".$Return[1]);
        }

        //Log plugin file to database
        foreach ($scanned_directory AS $value) {
            $this->logpluginfile($value, $param[0], $source, $target);
        }

        //Installation INSTALL.SQL
        if (file_exists($ThisPluginDirectory."/sql/install.sql")) {
            $this->sqlexecute($ThisPluginDirectory."/sql/install.sql");
        }
        if (file_exists($ThisPluginDirectory."/SQL/install.sql")) {
            $this->sqlexecute($ThisPluginDirectory."/SQL/install.sql");
        }
        if (file_exists($ThisPluginDirectory."/sql/INSTALL.SQL")) {
            $this->sqlexecute($ThisPluginDirectory."/sql/INSTALL.SQL");
        }
        if (file_exists($ThisPluginDirectory."/SQL/INSTALL.SQL")) {
            $this->sqlexecute($ThisPluginDirectory."/SQL/INSTALL.SQL");
        }

        $Query = "SELECT group_id, id FROM menu WHERE title = 'Plugins'";
        $res = $db->sql_query($Query);

        $ids = array();
        $ids = $db->sql_fetch_array($res, MYSQLI_ASSOC);

        $tree = new TreeInterval($db, "menu", array("id_parent" => "parent_id"), array("group_id" => $ids["group_id"]));

        include($ThisPluginDirectory."/"."install.php");

        $ThisInstallation = new install();

        $sql = "START TRANSACTION;";
        foreach ($ThisInstallation->menu_install() AS $key => $value)
        {
            $tree->add($value , $ids["id"]);

            //On met en base le fait que le plugin est installé.
            $sql .= "INSERT INTO plugin_menu (id_plugin_main, url) SELECT ".$ids["group_id"].", '".$value["url"]."';";
        }

        //On met en base le fait que le plugin est installé.
        $sql .= "UPDATE plugin_main SET est_actif = 1 WHERE id = ".$param[0].";";
        $sql .= "COMMIT;";
        $db->sql_query($sql);

        echo "Installation OK !";
    }

    public function copyfile($file, $source, $target, $nest = 1)
    {
        $Return = array();
        $Return[0] = true;
        $Mode = "";

        if (is_dir($source.$file)) {
            echo $nest." DIR ".$source.$file." -> ".$target.$file."<br>";
            //Fonctionnement si répertoire
            if (!file_exists($target.$file)) {
                mkdir($target.$file);
                $Mode = "DirMaking";
            }

            //On scan l'archive pour faire les copy de fichier.
            $scanned_directory = array_diff(scandir($source.$file), array('..', '.', '.gitmodules', 'sql', 'install.php', 'uninstall.php', 'README.md', 'image.jpg'));

            foreach ($scanned_directory AS $value) {
                if ($Return[0]==true)
                {
                    $Return = $this->copyfile($value, $source.$file."/", $target.$file."/", $nest+1);
                }
            }
        } else {
            echo $nest." FILE ".$source.$file." -> ".$target.$file."<br>";
            //Fonction si fichier
            if (!file_exists($target.$file)) {
                if (false === copy($source.$file, $target.$file)) {
                    $Return[0] = false;
                    $Return[1] = "Abort : error during copy of ".$target.$file;
                }
                else
                {
                    $Mode = "FileCoping";
                }
            } else {
                $Return[0] = false;
                $Return[1] = "Abort : a file already exists ".$target.$file;
            }
        }

        if ($Return[0] == false) {
            //if false on efface si on a ajouter
            if ($Mode == "DirMaking")
            {
                rmdir($target.$file);
            }
            if ($Mode == "FileCoping")
            {
                unlink($target.$file);
            }
        }

        return $Return;
    }

    public function logpluginfile($handle, $pluginid, $source, $target)
    {
        $db = $this->di['db']->sql(DB_DEFAULT);

        if (is_dir($source."/".$handle)) {
            //Fonctionnement si répertoire
            //On scan l'archive pour faire les inserts dans la date de données de fichier.
            $scanned_directory = array_diff(scandir($source."/".$handle), array('..', '.', '.gitmodules', 'sql', 'install.php', 'uninstall.php', 'README.md', 'image.jpg'));

            foreach ($scanned_directory AS $value) {
                $this->logpluginfile($value, $pluginid, $source.$handle."/", $target.$handle."/");
            }
        } else {
            //Fonctionnement si fichier
            $sql = "REPLACE INTO plugin_file (id_plugin_main, file, md5) SELECT ".$pluginid.", '".$target.$handle."', '".md5_file($target.$handle)."'";
            $db->sql_query($sql);
        }

    }

    private function sqlexecute($filename)
    {
        $db = $this->di['db']->sql(DB_DEFAULT);

        $sql = file_get_contents($filename);
        $db->sql_query($sql);
    }

    public function remove($param)
    {
        if (!isset($param[0])) {
            Throw new \Exception("No plugin Id provided");
        }

        
    }
}