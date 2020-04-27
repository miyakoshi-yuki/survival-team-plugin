<?php

$sti = new StiMemberClass ;
class StiMemberClass{

  public function __construct(){
    global $wpdb;
    $this->table_name = $wpdb->prefix . 'stiTable';
    $this->sti_db_version = "1.1.6";

    add_action('admin_init', array($this,'sti_contents'));
    add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );

    if(get_option( 'sti_db_version' ) !== $this->sti_db_version){
      $this->sti_plugin_start();
    }

    //インストール時に実行
    if(function_exists('add_action')) {
    add_action( 'activate_'.STI_PLUGIN_BASENAME, array( $this,'sti_plugin_start'), 10, 0 );
    }

  }

  //##################################
  //条件分岐
  //##################################
  public function sti_contents(){
    //メニューからだと条件分岐でヘッダーの再定義になるので
    //コンストラクタ下で実行する

    if($this->is_sti_admin()){
      if(isset($_POST['edit'])){
        $this->sti_edit();
      }else if(isset($_POST['created'])){
        $this->sti_created();
      }else if(isset($_GET['delete'])){
        $this->sti_delete();
      }
    }
  }

  //##################################
  //表示(new)
  //##################################
 public function sti_new(){

   global $wpdb;
   $sti_sql="SELECT * FROM ".$this->table_name." order by sort desc";

   //**管理画面SQL文を実行する（select文のみ）の処理
   $sti_sql_result = $wpdb->get_results($sti_sql,OBJECT);

   foreach($sti_sql_result as $key=>$val){
     $val->created = date_format(date_create($val->created), 'Y/m/d');
     if( $val->sex == "male" ){
       $val->jpn_sex = "男性";
     } else if ( $val->sex == "female" ) {
       $val->jpn_sex = "女性";
     }
     if($val->age !== ""){
       $val->jpn_age = substr($val->age, 0, -1) . "代";
     }
     $val->href = "?page=sti_admin&stiContact_id=".$val->id;
     $val->href_d = $val->href."&delete=1";
   }

   $array = get_option("sti_posion_name");
   $posion_show = "\t<option value=''>無役職</option>\n";
   for ( $i = 0; $i < count( $array ); $i++ ) {
     $posion_show .= "\t<option value=\"{$array[$i]}\">{$array[$i]}</option>\n";
   }
   $box = $posion_show;
   $posion_show2 = "\t<option value=''></option>\n";
   for ( $i = 0; $i < count( $array ); $i++ ) {
     $posion_show2 .= "\t<option value=\"{$array[$i]}\">{$array[$i]}</option>\n";
   }
   $box2 = $posion_show2;

  function is_checked($column,$id){
    global $wpdb;
    global $sti;
    if($wpdb->get_var("SELECT ".$column." FROM ".$sti->table_name." WHERE id = ".$id) == 1){
      echo "checked='checked'";
    }
  }

  $a = $_GET['completed'] ? "<div class='updated fade'><p><strong>保存されました</strong></p></div>" : "";
  $b = $_GET['delete_completed'] ? "<div class='updated fade'><p><strong>削除されました</strong></p></div>" : "";
  $c = $_GET['edit_completed'] ? "<div class='updated fade'><p><strong>編集されました</strong></p></div>" : "";
  $nonce = wp_nonce_field('stidatabase');

  $form = require_once ('admin_member.php');
  echo $form;

 }


  //##################################
  //保存(create)
  //##################################
  public function sti_created(){
    if(
      (isset($_POST['sti_name']) )
      && check_admin_referer('stidatabase')
    ){
      //更新処理処理
      global $wpdb;
      $name = $wpdb->escape($_POST['sti_name']);
      $posion = $wpdb->escape($_POST['sti_posion']);
      $sex = $wpdb->escape($_POST['sti_sex']);
      $age = $wpdb->escape($_POST['sti_age']);
      $image = $wpdb->escape($_POST['sti_image']);
      $comment = $wpdb->escape($_POST['sti_comment']);
      $wpdb->insert( $this->table_name,
        array(
          'created'=>current_time('mysql'),
          'name' => $name,
          'posion' => $posion,
          'sex' => $sex,
          'age' => $age,
          'image' => $image,
          'comment' => $comment,
        )
      );
      //再読み込みを防ぐためリダイレクト
      wp_redirect( add_query_arg( array(
        'stiContact_id' => false,
        'completed' => 1,
        'delete'=> false,
        'delete_completed' => false,
        'edit'=> false,
        'edit_completed' => false,
      ) ) );
      exit();
    }
  }

  //##################################
  //削除(dalate)
  //##################################
  public function sti_delete() {
    global $wpdb;
    $sti_sql="DELETE FROM ".$this->table_name." where id = ".$wpdb->escape($_GET['stiContact_id']);
    $wpdb->query($sti_sql);
    //再読み込みを防ぐためリダイレクト
    $uri = add_query_arg(
      array(
        'stiContact_id' => false,
        'completed' => false,
        'delete'=> false,
        'delete_completed' => 1,
        'edit'=> false,
        'edit_completed' => false,
      )
    );
    wp_redirect($uri);
    exit();
  }

  //##################################
  //編集(edit)
  //##################################
  public function sti_edit(){
    if(
      (isset($_POST['sti_edit_name']) )
      && check_admin_referer('stidatabase')
    ){
      //更新処理処理
      global $wpdb;
      $id = $_POST['sti_edit_id'];
      $name = $wpdb->escape($_POST['sti_edit_name']);
      $posion = $wpdb->escape($_POST['sti_edit_posion']);
      $sex = $wpdb->escape($_POST['sti_edit_sex']);
      $age = $wpdb->escape($_POST['sti_edit_age']);
      $image = $wpdb->escape($_POST['sti_edit_image']);
      $comment = $wpdb->escape($_POST['sti_edit_comment']);
      $wpdb->update( $this->table_name,
        array(
          'name' => $name,
          'posion' => $posion,
          'sex' => $sex,
          'age' => $age,
          'image' => $image,
          'comment' => $comment,
        ),
        array(
          'id' => $id
        )
      );
      //再読み込みを防ぐためリダイレクト
      wp_redirect( add_query_arg( array(
        'stiContact_id' => false,
        'completed' => false,
        'delete'=> false,
        'delete_completed' => false,
        'edit'=> false,
        'edit_completed' => 1,
      ) ) );
      exit();
    }
  }

   //##################################
   //プラグインを有効化したとき（インストール）
   //##################################
  public function sti_plugin_start(){
    global $wpdb;
    if(
      $wpdb->get_var("SHOW TABLES LIKE '$this->table_name'")
      != $this->table_name
      || get_option( 'sti_db_version' ) !== $this->sti_db_version
    )
    {
      $sql = "CREATE TABLE " . $this->table_name . " (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      sort text,
      created datetime DEFAULT NULL,
      honorific text,
      name text NOT NULL,
      posion text,
      sex text,
      age text,
      image text,
      comment text,
      fix text,
      UNIQUE KEY id (id)
      );";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

      if(!get_option( 'sti_db_version' )){add_option("sti_db_version", $this->sti_db_version);}else{
        update_option("sti_db_version", $this->sti_db_version);
      }

      add_option("sti_posion_name",array("隊長"));
    }

  }

  //##################################
  //管理画面表示
  //##################################

  public function sti_admin_page(){
    echo $this->sti_new();
  }

  public function add_plugin_page(){
    add_menu_page('隊員管理', '隊員管理', 'administrator', 'sti_admin',array($this,'sti_admin_page'));
  }

  public function is_sti_admin(){
    if(
      strpos($_SERVER["REQUEST_URI"],'sti_admin')
      !== false
    ){
    return true;
    }else{
    return false;
    }
  }

}
