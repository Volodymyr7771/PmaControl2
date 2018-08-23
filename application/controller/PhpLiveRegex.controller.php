<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \Glial\Synapse\Controller;

class PhpLiveRegex extends Controller
{

    public function index()
    {


        $this->di['js']->addJavascript(array(__CLASS__.'/index.js'));
    }

    public function evaluate($param)
    {
        $this->view        = false;
        $this->layout_name = false;



        $fcts = $this->pregView($_POST['regex_1'], $_POST['regex_2'], $_POST['replacement'], $_POST['examples']);



        $ret = array();
        foreach ($fcts as $name => $data) {
            $ret[$name] = '<input class="form-control" onClick="this.focus();this.select();" type="text" value="'.$data['cmd'].'" readonly="">';
            $ret[$name] .= '<pre>'.print_r($data['data'], true).'</pre>';
        }

        //preg_match("/(.*), (.*)/", $input_line, $output_array);
        //debug($_POST);
        file_put_contents("/tmp/gg", json_encode($ret));

        echo json_encode($ret);

        //echo '{"preg_match":"<input class="form-control" onClick="this.focus();this.select();" type="text" value="preg_match(&quot;\/(.*), (.*)\/&quot;, $input_line, $output_array);" readonly=""><div class="data-structure"><!-- ref#0 --><div><div class="ref"><span data-input><\/span><span data-output><span data-array>array<\/span><i>(<\/i><span data-gLabel>3<\/span><span data-toggle data-exp><\/span><span data-group><span data-table><span data-row><span data-cell><span data-key data-tip="0">0<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1">last_name, first_name<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">1<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="2">last_name<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">2<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="3">first_name<\/span><\/span><\/span><\/span><\/span><i>)<\/i><\/span><div><span data-row><span data-cell><span data-title>Key: integer<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(21)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(9)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(10)<\/span><\/span><\/span><\/div><\/div><\/div><!-- \/ref#1 --><!-- ref#1 --><div><div class="ref"><span data-input><\/span><span data-output><span data-array>array<\/span><i>(<\/i><span data-gLabel>3<\/span><span data-toggle data-exp><\/span><span data-group><span data-table><span data-row><span data-cell><span data-key data-tip="0">0<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1">bjorge, philip<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">1<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="2">bjorge<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">2<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="2">philip<\/span><\/span><\/span><\/span><\/span><i>)<\/i><\/span><div><span data-row><span data-cell><span data-title>Key: integer<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(14)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(6)<\/span><\/span><\/span><\/div><\/div><\/div><!-- \/ref#2 --><!-- ref#2 --><div><div class="ref"><span data-input><\/span><span data-output><span data-array>array<\/span><i>(<\/i><span data-gLabel>3<\/span><span data-toggle data-exp><\/span><span data-group><span data-table><span data-row><span data-cell><span data-key data-tip="0">0<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1">kardashian, kim<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">1<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="2">kardashian<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">2<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="3">kim<\/span><\/span><\/span><\/span><\/span><i>)<\/i><\/span><div><span data-row><span data-cell><span data-title>Key: integer<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(15)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(10)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(3)<\/span><\/span><\/span><\/div><\/div><\/div><!-- \/ref#3 --><!-- ref#3 --><div><div class="ref"><span data-input><\/span><span data-output><span data-array>array<\/span><i>(<\/i><span data-gLabel>3<\/span><span data-toggle data-exp><\/span><span data-group><span data-table><span data-row><span data-cell><span data-key data-tip="0">0<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1">mercury, freddie<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">1<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="2">mercury<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">2<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="2">freddie<\/span><\/span><\/span><\/span><\/span><i>)<\/i><\/span><div><span data-row><span data-cell><span data-title>Key: integer<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(16)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(7)<\/span><\/span><\/span><\/div><\/div><\/div><!-- \/ref#4 --><!-- ref#4 --><div><div class="ref"><span data-input><\/span><span data-output><span data-array>array<\/span><i>(<\/i><i>)<\/i><\/span><\/div><\/div><!-- \/ref#5 --><!-- ref#5 --><div><div class="ref"><span data-input><\/span><span data-output><span data-array>array<\/span><i>(<\/i><i>)<\/i><\/span><\/div><\/div><!-- \/ref#6 --><\/div><p><strong>note:<\/strong> preg_match is run on each line of input.<\/p>","preg_match_all":"<input class="form-control" onClick="this.focus();this.select();" type="text" value="preg_match_all(&quot;\/(.*), (.*)\/&quot;, $input_lines, $output_array);" readonly=""><div class="data-structure"><!-- ref#6 --><div><div class="ref"><span data-input><\/span><span data-output><span data-array>array<\/span><i>(<\/i><span data-gLabel>3<\/span><span data-toggle data-exp><\/span><span data-group><span data-table><span data-row><span data-cell><span data-key data-tip="0">0<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-array>array<\/span><i>(<\/i><span data-gLabel>4<\/span><span data-toggle data-exp><\/span><span data-group><span data-table><span data-row><span data-cell><span data-key data-tip="0">0<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1">last_name, first_name<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">1<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="2">bjorge, philip<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">2<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="3">kardashian, kim<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">3<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="4">mercury, freddie<\/span><\/span><\/span><\/span><\/span><i>)<\/i><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">1<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-array>array<\/span><i>(<\/i><span data-gLabel>4<\/span><span data-toggle data-exp><\/span><span data-group><span data-table><span data-row><span data-cell><span data-key data-tip="0">0<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="5">last_name<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">1<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="6">bjorge<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">2<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="7">kardashian<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">3<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="8">mercury<\/span><\/span><\/span><\/span><\/span><i>)<\/i><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">2<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-array>array<\/span><i>(<\/i><span data-gLabel>4<\/span><span data-toggle data-exp><\/span><span data-group><span data-table><span data-row><span data-cell><span data-key data-tip="0">0<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="7">first_name<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">1<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="6">philip<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">2<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="9">kim<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">3<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="8">freddie<\/span><\/span><\/span><\/span><\/span><i>)<\/i><\/span><\/span><\/span><\/span><i>)<\/i><\/span><div><span data-row><span data-cell><span data-title>Key: integer<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(21)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(14)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(15)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(16)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(9)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(6)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(10)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(7)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(3)<\/span><\/span><\/span><\/div><\/div><\/div><!-- \/ref#7 --><\/div>","preg_replace":"<input class="form-control" onClick="this.focus();this.select();" type="text" value="preg_replace(&quot;\/(.*), (.*)\/&quot;, &quot;$0 --&gt; $2 $1&quot;, $input_lines);" readonly=""><div class="data-structure"><!-- ref#7 --><div><div class="ref"><span data-input><\/span><span data-output><span data-string data-tip="0">last_name, first_name --&gt; first_name last_name\nbjorge, philip --&gt; philip bjorge\nkardashian, kim --&gt; kim kardashian\nmercury, freddie --&gt; freddie mercury\n\nxfgnxfgnxfgnfgnngf<\/span><\/span><div><span data-row><span data-cell><span data-title>string(171)<\/span><\/span><\/span><\/div><\/div><\/div><!-- \/ref#8 --><\/div>","preg_filter":"","preg_grep":"<input class="form-control" onClick="this.focus();this.select();" type="text" value="preg_grep(&quot;\/(.*), (.*)\/&quot;, explode(&quot;\\n&quot;, $input_lines));" readonly=""><div class="data-structure"><!-- ref#8 --><div><div class="ref"><span data-input><\/span><span data-output><span data-array>array<\/span><i>(<\/i><span data-gLabel>4<\/span><span data-toggle data-exp><\/span><span data-group><span data-table><span data-row><span data-cell><span data-key data-tip="0">0<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1">last_name, first_name<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">1<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="2">bjorge, philip<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">2<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="3">kardashian, kim<\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">3<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="4">mercury, freddie<\/span><\/span><\/span><\/span><\/span><i>)<\/i><\/span><div><span data-row><span data-cell><span data-title>Key: integer<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(21)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(14)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(15)<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(16)<\/span><\/span><\/span><\/div><\/div><\/div><!-- \/ref#9 --><\/div>","preg_split":"<input class="form-control" onClick="this.focus();this.select();" type="text" value="preg_split(&quot;\/(.*), (.*)\/&quot;, $input_line);" readonly=""><div class="data-structure"><!-- ref#9 --><div><div class="ref"><span data-input><\/span><span data-output><span data-array>array<\/span><i>(<\/i><span data-gLabel>2<\/span><span data-toggle data-exp><\/span><span data-group><span data-table><span data-row><span data-cell><span data-key data-tip="0">0<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1"><\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">1<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1"><\/span><\/span><\/span><\/span><\/span><i>)<\/i><\/span><div><span data-row><span data-cell><span data-title>Key: integer<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(0)<\/span><\/span><\/span><\/div><\/div><\/div><!-- \/ref#10 --><!-- ref#10 --><div><div class="ref"><span data-input><\/span><span data-output><span data-array>array<\/span><i>(<\/i><span data-gLabel>2<\/span><span data-toggle data-exp><\/span><span data-group><span data-table><span data-row><span data-cell><span data-key data-tip="0">0<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1"><\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">1<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1"><\/span><\/span><\/span><\/span><\/span><i>)<\/i><\/span><div><span data-row><span data-cell><span data-title>Key: integer<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(0)<\/span><\/span><\/span><\/div><\/div><\/div><!-- \/ref#11 --><!-- ref#11 --><div><div class="ref"><span data-input><\/span><span data-output><span data-array>array<\/span><i>(<\/i><span data-gLabel>2<\/span><span data-toggle data-exp><\/span><span data-group><span data-table><span data-row><span data-cell><span data-key data-tip="0">0<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1"><\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">1<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1"><\/span><\/span><\/span><\/span><\/span><i>)<\/i><\/span><div><span data-row><span data-cell><span data-title>Key: integer<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(0)<\/span><\/span><\/span><\/div><\/div><\/div><!-- \/ref#12 --><!-- ref#12 --><div><div class="ref"><span data-input><\/span><span data-output><span data-array>array<\/span><i>(<\/i><span data-gLabel>2<\/span><span data-toggle data-exp><\/span><span data-group><span data-table><span data-row><span data-cell><span data-key data-tip="0">0<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1"><\/span><\/span><\/span><span data-row><span data-cell><span data-key data-tip="0">1<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1"><\/span><\/span><\/span><\/span><\/span><i>)<\/i><\/span><div><span data-row><span data-cell><span data-title>Key: integer<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(0)<\/span><\/span><\/span><\/div><\/div><\/div><!-- \/ref#13 --><!-- ref#13 --><div><div class="ref"><span data-input><\/span><span data-output><span data-array>array<\/span><i>(<\/i><span data-gLabel>1<\/span><span data-toggle data-exp><\/span><span data-group><span data-table><span data-row><span data-cell><span data-key data-tip="0">0<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1"><\/span><\/span><\/span><\/span><\/span><i>)<\/i><\/span><div><span data-row><span data-cell><span data-title>Key: integer<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(0)<\/span><\/span><\/span><\/div><\/div><\/div><!-- \/ref#14 --><!-- ref#14 --><div><div class="ref"><span data-input><\/span><span data-output><span data-array>array<\/span><i>(<\/i><span data-gLabel>1<\/span><span data-toggle data-exp><\/span><span data-group><span data-table><span data-row><span data-cell><span data-key data-tip="0">0<\/span><\/span><span data-cell><i>=&gt;<\/i><\/span><span data-cell><span data-string data-tip="1">xfgnxfgnxfgnfgnngf<\/span><\/span><\/span><\/span><\/span><i>)<\/i><\/span><div><span data-row><span data-cell><span data-title>Key: integer<\/span><\/span><\/span><\/div><div><span data-row><span data-cell><span data-title>string(18)<\/span><\/span><\/span><\/div><\/div><\/div><!-- \/ref#15 --><\/div><p><strong>note:<\/strong> preg_split_result is run on each line of input.<\/p>"}';
    }

    public function pregView($regex, $options, $replace, $data)
    {

        $preg['preg_match']['cmd']     = "preg_match('/".htmlentities($regex)."/'".$options.", \$input_line, \$output_array);";
        $preg['preg_match_all']['cmd'] = "preg_match_all('/".htmlentities($regex)."/'".$options.", \$input_line, \$output_array);";
        $preg['preg_replace']['cmd']   = "\$result = preg_replace('/".htmlentities($regex)."/'".$options.",'".$replace."' ,\$input_line);";
        $preg['preg_grep']['cmd']      = "preg_replace('/".$regex."/'".$options.",'\$replace' ,\$input_line);";
        $preg['preg_split']['cmd']     = "preg_replace('/".$regex."/'".$options.",'\$replace' ,\$input_line);";


        $lines = explode("\n", $data);


        $preg['preg_match']['data'] = '';

        foreach ($lines as $line) {

            $output_array = array();
            preg_match("/".$regex."/".$options, $line, $output_array);


            $gg = array_map("htmlentities", $output_array);

            $preg['preg_match']['data'] .= print_r($gg, true);
        }

        $output_array                   = array();
        preg_match_all("/".$regex."/".$options, $data, $output_array);
        $preg['preg_match_all']['data'] = $output_array;



        foreach($preg['preg_match_all']['data'] as $key => $value)
        {
            $preg['preg_match_all']['data'][$key] = array_map("htmlentities", $preg['preg_match_all']['data'][$key]);
        }

        


        $out = preg_replace('/'.$regex.'/'.$options, $replace, $data);

        $preg['preg_replace']['data'] = htmlentities($out);


        $preg['preg_grep']['data']    = "dfgdfsgf";
        $preg['preg_split']['data']   = "dfgdfsgf";


        return $preg;
    }
}