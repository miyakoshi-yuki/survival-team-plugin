<?php

$sti2 = new StiScheduleClass ;
class StiScheduleClass{

  public $sti_db_version = "1.0";

  public function __construct(){
    global $wpdb;
    $this->table_name = $wpdb->prefix . 'stiTable2';

      add_action('admin_init', array($this,'sti_contents'));
      add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );

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
    if($this->is_sti_schedule()){
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
  //保存(create)
  //##################################
  public function sti_created(){
    if(
      (isset($_POST['sti_field']) )
      && check_admin_referer('stidatabase')
    ){
      //更新処理処理
      global $wpdb;
      $day = $wpdb->escape($_POST['sti_day']);
      $field = $wpdb->escape($_POST['sti_field']);
      $fee = $wpdb->escape($_POST['sti_fee']);
      $wpdb->insert( $this->table_name,
        array(
          'created'=>current_time('mysql'),
          'day' => $day,
          'field' => $field,
          'fee' => $fee,
        )
      );
      //再読み込みを防ぐためリダイレクト
      wp_redirect( add_query_arg( array(
        'stiContact_id' => false,
        'completed' => 1,
        'delete'=> false,
        'delete_completed' => false,
        'edit'=> false,
        'edit_completed' => false) ) );
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
      (isset($_POST['sti_edit_field']) )
      && check_admin_referer('stidatabase')
    ){
      //更新処理処理
      global $wpdb;
      // wp_die();
      $id = $_POST['sti_edit_id'];
      $day = $_POST['sti_edit_day'];
      $field = $wpdb->escape($_POST['sti_edit_field']);
      $fee = $wpdb->escape($_POST['sti_edit_fee']);
      $wpdb->update( $this->table_name,
        array(
          'day' => $day,
          'field' => $field,
          'fee' => $fee,
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
        'edit_completed' => 1) ) );
      exit();
    }
  }

   //##################################
   //表示(new)
   //##################################

  public function sti_schedule_new(){

    global $wpdb;
    $sti_sql="SELECT * FROM ".$this->table_name." order by day asc";

    //**管理画面SQL文を実行する（select文のみ）の処理
    $sti_sql_result = $wpdb->get_results($sti_sql,OBJECT);

    foreach($sti_sql_result as $key=>$val){
      $created = date_format(date_create($val->created), 'Y/m/d');
      $val->href = "?page=sti_schedule&stiContact_id=".$val->id;
      $val->href_d = $val->href."&delete=1";
    }

    $a = $_GET['completed'] ? "<div class='updated fade'><p><strong>保存されました</strong></p></div>" : "";
    $b = $_GET['delete_completed'] ? "<div class='updated fade'><p><strong>削除されました</strong></p></div>" : "";
    $c = $_GET['edit_completed'] ? "<div class='updated fade'><p><strong>編集されました</strong></p></div>" : "";
    $nonce = wp_nonce_field('stidatabase');
    $form = require_once ('admin_schedule.php');
    echo $form;

  }

   //##################################
   //プラグインを有効化したとき（インストール）
   //##################################
  public function sti_plugin_start(){
    global $wpdb;
    global $sti_db_version;

    if(
      $wpdb->get_var("SHOW TABLES LIKE '$this->table_name'")
      != $this->table_name
    )
    {
      $sql = "CREATE TABLE " . $this->table_name . " (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      created datetime DEFAULT NULL,
      day text NOT NULL,
      field text,
      fee text,
      people text,
      UNIQUE KEY id (id)
      );";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
    }

  }

  //##################################
  //管理画面表示
  //##################################

  public function sti_schedule_page(){
    echo $this->sti_schedule_new();
  }

  public function add_plugin_page(){
    add_menu_page('活動予定', '活動予定', 'administrator',
    'sti_schedule',array($this,'sti_schedule_page'));
  }

  public function is_sti_schedule(){
    if(
      strpos($_SERVER["REQUEST_URI"],'sti_schedule')
      !== false
    ){
    return true;
    }else{
    return false;
    }
  }

}
