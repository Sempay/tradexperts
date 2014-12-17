<?php

/**
 * @file
 * Administration forms for the HyperComments module.
 */

/**
 * Menu callback; Displays the administration settings for HyperComments.
 */
function hypercomments_admin_settings() {
  drupal_add_js(drupal_get_path('module', 'hypercomments').'/hypercomments.js');

  $site_name = (variable_get('site_name') && strlen(variable_get('site_name')) != 0) ? variable_get('site_name') : 'drupal_site';
  drupal_add_js('jQuery(document).ready(function(){
      var hc_instanse = new HyperComments("'.variable_get('hypercomments_url').'","'.$site_name.'", "'.$GLOBALS['base_url'].'");
      hc_instanse.init();
  })', 'inline');
  $form = array();

  if(!variable_get('hypercomments_wid') || strlen(variable_get('hypercomments_wid')) == 0){
    $form['hypercommetns_wid'] = array(
      '#type' => 'button',
      '#value' => t('Login with Google'),
    );
    drupal_add_js('jQuery(document).ready(function(){jQuery("#edit-submit").hide();})', 'inline');
  }else{
    $form['settings'] = array(
      '#type' => 'vertical_tabs',
      '#weight' => 50,
    );
    // Admin Panel.
    $form['admin'] = array(
      '#type' => 'fieldset',
      '#title' => t('Admin'),
      '#group' => 'settings',
      '#description' => hypercomments_show_admin(variable_get('hypercomments_wid'))
    );
    // Settings.
    $form['general'] = array(
      '#type' => 'fieldset',
      '#title' => t('Settings'),
      '#group' => 'settings',
    );
    $types = node_type_get_types();
    $options = array();
    foreach ($types as $type) {
      $options[$type->type] = $type->name;
    }
    $form['general']['hypercomments_nodetypes'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Node Types'),
      '#description' => t('Apply comments to only the following node types.'),
      '#default_value' => variable_get('hypercomments_nodetypes', array()),
      '#options' => $options,
    );
    $form['general']['hypercomments_location'] = array(
      '#type' => 'select',
      '#title' => t('Location'),
      '#description' => '',
      '#default_value' => variable_get('hypercomments_location', 'content_area'),
      '#options' => array(
        'content_area' => t('Content Area'),
      ),
    );
    $form['general']['hypercomments_weight'] = array(
      '#type' => 'select',
      '#title' => t('Weight'),
      '#description' => t('When the comments are displayed in the content area, you can change the position at which they will be shown.'),
      '#default_value' => variable_get('hypercomments_weight', 50),
      '#options' => drupal_map_assoc(array(-100, -75, -50, -25, 0, 25, 50, 75, 100)),
      '#states' => array(
        'visible' => array(
          'select[name="hypercomments_location"]' => array('value' => 'content_area'),
        ),
      ),
    );
    // Export.
    $form['export'] = array(
      '#type' => 'fieldset',
      '#title' => t('Export'),
      '#group' => 'settings',
      '#description' => t('You can export the comments from Drupal to HyperComments.'),
    );
    $form['export']['export_button'] = array(
      '#type' => 'button',
      '#title' => t('Export comments'),
      '#value' => t('Export comments'),
    );
  }
  return system_settings_form($form);
}
/**
* Save widget ID
*/
function hypercomments_admin_savewid() {
  if(isset($_REQUEST['wid'])){
    variable_set('hypercomments_wid', $_REQUEST['wid']);
    variable_set('hypercomments_secret_key', $_REQUEST['secret_key']);
    echo drupal_json_output(array('result'=>'success'));
    drupal_exit();
  }else{
    echo drupal_json_output(array('result'=>'error'));
    drupal_exit();
  }
}
/**
* Show widget administration
*/
function hypercomments_show_admin($wid) {
  global $language;
  if(in_array($language->language, array('en','ru','ua','de','fr')))
  {
    $lang = $language->language;
  }else{
    $lang = 'en';
  } 
  $adm = '<div id="widget" style="margin: 0 auto;"></div>
  <script type="text/javascript">
      var _hcp = {};
      _hcp.widget = "Adm";
      _hcp.append = "#widget";   
      _hcp.lang = "'.$lang.'";
      _hcp.widget_id = '.$wid.';';
    if(variable_get('hypercomments_debug')){
      $adm.= ' 
      _hcp.test = 1;
      _hcp.debug = 1;
      _hcp.dev = 1;';
    }
  $adm.= '(function() { 
          var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true;
          hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://widget.hypercomments.com/apps/js/hc.js";
          var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(hcc, s.nextSibling); 
      })();
  </script>';
  return $adm;
}
/**
* Synchronization comments
*/
function hypercomments_admin_notify() {
  module_load_include('php', 'hypercomments', 'hypercomments');
  if(isset($_REQUEST['data']) && isset($_REQUEST['time']) && isset($_REQUEST['signature'])){
    $data      = $_POST['data'];
    $time      = $_POST['time'];
    $signature = $_POST['signature'];

    $secret_key = variable_get('hypercomments_secret_key');
    if($secret_key) { 
      $HC = new HyperComments($secret_key);
      $res = $HC->hc_notify($data, $time, $signature);     
      echo drupal_json_output(array('result'=>$res));
      drupal_exit();
    }       
  }
}
/**
* Get all Drupal nodes
*/
function hypercomments_admin_get_nodes() {
  $sql = "SELECT nid FROM node";
  $rows = db_query($sql);
  $str_id = '';
  foreach ($rows as $row) {
    $str_id.= $row->nid.',';
  }
  echo drupal_json_output(array('result'=>'success', 'data'=>substr($str_id, 0, -1)));
  drupal_exit();
}
/**
* Export comments
*/
function hypercomments_admin_make_export() {
  $nid = $_REQUEST['node'];
  $sql = "SELECT nid, title FROM node WHERE nid=".$nid;
  $rows = db_query($sql);
  foreach ($rows as $page) {
    $dhx = hypercomments_export($page);
    if($dhx){
      $dir_root  = dirname(dirname(dirname(dirname(__FILE__)))).'/default';
      if(is_dir($dir_root)){
        $file_name = time().'_'.rand(0,100).'.xml';                    
        $file_root = $dir_root.'/'.$file_name;                      
        $file_path = $GLOBALS['base_url'].'/sites/default/'.$file_name;
        $write_file = file_put_contents($file_root, $dhx);
        if($write_file){
          $json_arr = array(
            'service'     => 'drupal',
            'pageID'      => $nid,
            'widget_id'   => variable_get('hypercomments_wid'),
            'request_url' => $file_path,                      
            'result_url'  => $GLOBALS['base_url'].'/admin/config/services/hypercomments/delete_xml?xml='.$file_name,
            'result'      => 'success'
          );                                                                                                         
        }else{
          $json_arr = array('result'=>'error','description'=>'Error writing XML', 'code'=>101);
        }
      }else{
        $json_arr = array('result'=>'error','description'=>'Error no dir', 'code'=>102);
      }
    }else{
      $json_arr = array('result'=>'error','description'=>'Error xml comment', 'code'=>103);
    }
    $response_array[] = $json_arr;
  }
  echo drupal_json_output($response_array);
  drupal_exit();
}
/**
* Make node xml file with commetns
*/
function hypercomments_export($page) {
  ob_start();
  echo '<?xml version="1.0" encoding="UTF-8"?>';
  ?>
  <output>
  <blogpost>
  <url><?php 
    $domain = str_replace('https://','',str_replace('http://','',str_replace('www.','', $GLOBALS['base_url'])));
    echo 'http://'.$domain.'/?q=node/'.$page->nid;
  ?></url>
  <title><?php echo $page->title;?></title>
  <comments>
  <?php
    $comments = getNodeComments($page->nid);
    foreach ($comments as $comment) { ?>
      
    <comment id='<?php echo $comment->cid;?>' parentid='<?php echo $comment->pid;?>'>
      <name><![CDATA[<?php echo $comment->name;?>]]></name>
      <email><?php echo $comment->mail;?></email>
      <ip><?php echo $comment->hostname;?></ip>
      <text><![CDATA[<?php echo $comment->comment_body_value;?>]]></text>
      <date><?php echo date('Y-m-d H:i:s',$comment->created);?></date>
    </comment>

  <?php 
    }
  ?>
  </comments>
  </blogpost>
  </output>
  <?php

  $output = ob_get_clean();
  return $output;
}
/**
* Get all node comments
*/
function getNodeComments($nodeid) {
  $sql = "SELECT c.*, t2.comment_body_value, u.mail FROM comment as c
          INNER JOIN users u ON u.uid = c.uid
          LEFT JOIN field_data_comment_body as t2 ON (c.cid = t2.entity_id) WHERE c.status = 1 AND c.nid = $nodeid";
  $all_comments = db_query($sql);
  return $all_comments;
}
/**
* Delete xml with comments
*/
function hypercomments_admin_delete_xml() {
  if(isset($_GET['result']) && $_GET['result'] == 'success'){
    $filename = dirname(dirname(dirname(dirname(__FILE__)))).'/default/'.$_GET['xml'];
    unlink($filename);
    echo drupal_json_output(array('result'=>'success'));
    drupal_exit();
  }else{
    echo drupal_json_output(array('result'=>'error'));
    drupal_exit();
  }
}

