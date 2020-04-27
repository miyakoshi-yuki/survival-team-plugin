<?php

/* ##################################
    隊員紹介ウィジェット
  ################################## */

$sti_widget01 = new StiWidget01;

class StiWidget01 extends WP_Widget{

  //コンストラクタでウィジェットを登録
  function __construct(){
    parent::__construct(
    'StiWidget01',	//ウィジェットID
    'サバイバルチーム隊員ウィジェット',		//ウィジェット名
    array('description' => 'サバイバルチームの隊員名を表示するウィジェットです。')	//ウィジェットの概要
    );

    global $wpdb;
    global $sti;

    $membersCountSql = "SELECT COUNT( * ) FROM " . $sti->table_name;
    $this->membersCount = $wpdb->get_var($membersCountSql);
    $membersSql = "SELECT * FROM " . $sti->table_name." order by sort asc";
    $this->membersArr = $wpdb->get_results( $membersSql, "ARRAY_A" );

    $this->hasFixArr =[];
    $this->noFixArr = [];
    for($i=0;$i<count($this->membersArr);$i++){
      if($this->membersArr[$i]['fix'] == "1"){
        array_push($this->hasFixArr,$this->membersArr[$i]);
      }else{
        array_push($this->noFixArr,$this->membersArr[$i]);
      }
    }

    $hasImageArr = [];
    for($i=0;$i<count($this->membersArr);$i++){
      if($this->membersArr[$i]['image'] !== ""
        ||$this->membersArr[$i]['comment'] !== ""
      ){
        array_push($hasImageArr,$this->membersArr[$i]);
      }
    }

    $this->hasImageFixArr = [];
    $this->hasImageNoFixArr = [];
    for($i=0;$i<count($hasImageArr);$i++){
      if($hasImageArr[$i]['fix'] == "1"){
        array_push($this->hasImageFixArr,$hasImageArr[$i]);
      }else{
        array_push($this->hasImageNoFixArr,$hasImageArr[$i]);
      }
    }

  }

  public function form( $instance ) {
    ?>

    <p>
      <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('画像の縦の長さ(px):'); ?></label>
      <input type="number" id="<?php echo $this->get_field_id('height'); ?>"min=0 name="<?php echo $this->get_field_name('height'); ?>" placeholder="デフォルト:200" value="<?php echo esc_attr( $instance['height'] ); ?>" size="3">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('people'); ?>"><?php _e('隊員名は何人まで表示する?:'); ?></label>
      <input type="number" id="<?php echo $this->get_field_id('people'); ?>" min="1" name="<?php echo $this->get_field_name('people'); ?>" placeholder="デフォルト:5" value="<?php echo esc_attr( $instance['people'] ); ?>" size="3">
    </p>
    <p>隊員名は管理画面で固定されたものが先頭にきます。</p>
    <?php
  }

  public function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['height'] = strip_tags($new_instance['height']);
    $instance['people'] = $new_instance['people'];
    return $instance;
  }


  //ウィジェットの表示
  public function widget($args, $instance){

    echo $args['before_widget'];

    echo $args['before_title'];

    echo "<div id='membersCount'>現在の隊員数".$this->membersCount."人!!</div>";

    echo $args['after_title'];

    echo "<div id='membersArr'>";

    if(!$instance['people']){
      $instance['people'] = 5;
    }

    $needNum = min(array(count($this->membersArr),$instance['people']));

    if($needNum - count($this->hasFixArr)>=0){

      for($i=0;$i<count($this->hasFixArr);$i++){
        $memberShow .=
          $this->hasFixArr[$i]['name']
          .$this->setHonorific($this->hasFixArr[$i]['id']);

          if($this->hasFixArr[$i]['posion']){
            $memberShow .=
              "["
              .$this->hasFixArr[$i]['posion']
              ."],";
          }else{
            $memberShow .= ",";
          }
      }

      if(
        $needNum - count($this->hasFixArr) >0
      ){

        $keys = array_keys($this->noFixArr);
        shuffle($keys);

        for(
          $i=0;
          $i < $needNum - count($this->hasFixArr);
          $i++
        ){

          $memberShow .=
            $this->noFixArr[$keys[$i]]['name']
            .$this->setHonorific($this->noFixArr[$keys[$i]]['id']);

            if($this->noFixArr[$keys[$i]]['posion']){
              $memberShow .=
                "["
                .$this->noFixArr[$keys[$i]]['posion']
                ."],";
            }else{
              $memberShow .= ",";
            }
        }
      }

    }else{

      for($i=0;$i<$needNum;$i++){
        $memberShow .=
          $this->hasFixArr[$i]['name']
          .$this->setHonorific($this->hasFixArr[$i]['id']);

          if($this->hasFixArr[$i]['posion']){
            $memberShow .=
              "["
              .$this->hasFixArr[$i]['posion']
              ."],";
          }else{
            $memberShow .= ",";
          }
      }

    }

    if(count($this->membersArr)-$instance['people']>0){
      $elseNum = ",その他".strip_tags(count($this->membersArr)-$instance['people'])."人";
    }else{
      $elseNum = "";
    }

    echo substr($memberShow, 0, -1).$elseNum." が活躍中！";

    echo "</div>";

    for($i=0;$i<count($this->hasImageFixArr);$i++){
      if($this->hasImageFixArr[$i]['image']){
        $slickFixImage .= "<li><img style= 'max-height:";

        if($instance['height']){
          $slickFixImage .= $instance['height'];
        }else{
          $slickFixImage .= 200;
        }

        $slickFixImage .= "px' src='"
          .$this->hasImageFixArr[$i]['image']
          ."' alt='image"
          .$i
          ."'>";
      }else{
        $slickFixImage .= "<li>";
      }

      $slickFixImage .=
        $this->hasImageFixArr[$i]['name'];

      if($this->hasImageFixArr[$i]['posion']){
        $slickFixImage .=
          "["
          .$this->hasImageFixArr[$i]['posion']
          ."]";
      }

      if($this->hasImageFixArr[$i]['comment'] == ""){
        $slickFixImage .= $this->setHonorific($this->hasImageFixArr[$i]['id'])." ⤴︎</li>";
      }else{
        $slickFixImage .= $this->setHonorific($this->hasImageFixArr[$i]['id'])." からの一言！<p>"
        .$this->hasImageFixArr[$i]['comment']
        ."</p></li>";
      }
    }

    $keys2 = array_keys($this->hasImageNoFixArr);
    shuffle($keys2);

    for($i=0;$i<count($this->hasImageNoFixArr);$i++){
      if($this->hasImageNoFixArr[$keys2[$i]]['image']){
        $slickNoFixImage .= "<li><img style= 'max-height:";

        if($instance['height']){
          $slickNoFixImage .= $instance['height'];
        }else{
          $slickNoFixImage .= 200;
        }

        $slickNoFixImage .= "px' src='"
          .$this->hasImageNoFixArr[$keys2[$i]]['image']
          ."' alt='image"
          .$i
          ."'>";
      }else{
        $slickNoFixImage .= "<li>";
      }

      $slickNoFixImage .=
        $this->hasImageNoFixArr[$keys2[$i]]['name'];

      if($this->hasImageNoFixArr[$keys2[$i]]['posion']){
        $slickNoFixImage .=
          "["
          .$this->hasImageNoFixArr[$keys2[$i]]['posion']
          ."]";
      }

      if($this->hasImageNoFixArr[$keys2[$i]]['comment'] == ""){
        $slickNoFixImage .= $this->setHonorific($this->hasImageNoFixArr[$keys2[$i]]['id'])." ⤴︎</li>";
      }else{
        $slickNoFixImage .= $this->setHonorific($this->hasImageNoFixArr[$keys2[$i]]['id'])." からの一言！<p>"
        .$this->hasImageNoFixArr[$keys2[$i]]['comment']
        ."</p></li>";
      }
    }

    echo "<ul id='sti_slick'>";

    echo $slickFixImage;

    echo $slickNoFixImage;

    echo "</ul>";

    echo $args['after_widget'];
  }

  public function setHonorific($id){
    global $wpdb;
    global $sti;
    if($wpdb->get_var("SELECT honorific FROM ".$sti->table_name." WHERE id = ".$id) == 1){
      return "さん";
    }
  }
}

add_action(
	'widgets_init',
	function(){
		register_widget('StiWidget01'); //ウィジェットのクラス名を記述
	}
);

/* ##################################
    男女グラフウィジェット
  ################################## */

$sti_widget02 = new StiWidget02;

class StiWidget02 extends WP_Widget{

  //コンストラクタでウィジェットを登録
  function __construct(){
    parent::__construct(
    'StiWidget02',	//ウィジェットID
    'サバイバルチーム男女別グラフウィジェット',		//ウィジェット名
    array('description' => 'サバイバルチームの男女グラフを表示するウィジェットです。')	//ウィジェットの概要
    );
    global $wpdb;
    global $sti;
    $membersSexSql = "SELECT sex FROM " . $sti->table_name;
    $membersSexRatio = $wpdb->get_col($membersSexSql);
    for($i=0; $i < count( $membersSexRatio ); $i++){
      if($membersSexRatio[$i] == 'male'){
        $this->membersSexArr['male']++;
      }else if($membersSexRatio[$i] == 'female'){
        $this->membersSexArr['female']++;
      }
    }
  }

  public function form( $instance ) {
    ?>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" placeholder="デフォルト:男女グラフ" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>">
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('タイトルの大きさ(px):'); ?></label>
        <input type="number" class="widefat" id="<?php echo $this->get_field_id('size'); ?>" min=0 placeholder="デフォルト:40" name="<?php echo $this->get_field_name('size'); ?>" value="<?php echo esc_attr( $instance['size'] ); ?>">
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('縦の長さ(height):'); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" placeholder="デフォルト:200" value="<?php echo esc_attr( $instance['height'] ); ?>" size="3">
    </p>
    <?php
  }

  public function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['size'] = strip_tags($new_instance['size']);
    $instance['height'] = strip_tags($new_instance['height']);
    return $instance;
  }

  //ウィジェットの表示
  public function widget($args, $instance){

    echo $args['before_widget'];

    echo $args['before_title'];

    echo "<p style='text-align:center;";

    if($instance['size']){
      echo "font-size:".$instance['size']."px'>";
    }else{
      echo "font-size: 40px'>";
    }

    if($instance['title']){
      echo $instance['title']."</p>";
    }else{
      echo "男女グラフ</p>";
    }

    echo $args['after_title'];

    if($instance['height']){
      if (is_numeric($instance['height'])) {
        $heightStyle = " height:".$instance['height'].'px;';
      } else {
        $heightStyle = " height:".$instance['height'].";";
      }
    }else{
      $heightStyle = " height:200px;";
    }

    echo "<div class='chart-container'><canvas id='stiBarChart' style='".$heightStyle."'></canvas></div>";

    echo $args['after_widget'];
  }
}

add_action(
	'widgets_init',
	function(){
		register_widget('StiWidget02'); //ウィジェットのクラス名を記述
	}
);

/* ##################################
    年齢グラフウィジェット
  ################################## */

$sti_widget03 = new StiWidget03;

class StiWidget03 extends WP_Widget{

  //コンストラクタでウィジェットを登録
  function __construct(){
    parent::__construct(
    'StiWidget03',	//ウィジェットID
    'サバイバルチーム年齢グラフウィジェット',		//ウィジェット名
    array('description' => 'サバイバルチームの年齢グラフを表示するウィジェットです。')	//ウィジェットの概要
    );

    global $wpdb;
    global $sti;
    $membersAgeSql = "SELECT age FROM " . $sti->table_name;
    $membersAgeRatio = $wpdb->get_col($membersAgeSql);

    for($m=1; $m <= 8; $m++){

      for($i=0; $i < count( $membersAgeRatio ); $i++){
        if($membersAgeRatio[$i] == $m . '0s'){
          ${"age" . $m . "0"}++;
        }
      }

      if(${"age" . $m . "0"} == 0){
        ${"age" . $m . "0"}=0;
      }

      $this->membersAgeArr[$m - 1] = ${"age" . $m . "0"};

    }
  }

  public function form( $instance ) {
    ?>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" placeholder="デフォルト:年齢グラフ" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>">
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('タイトルの大きさ(px):'); ?></label>
        <input type="number" class="widefat" id="<?php echo $this->get_field_id('size'); ?>" min=0 placeholder="デフォルト:40" name="<?php echo $this->get_field_name('size'); ?>" value="<?php echo esc_attr( $instance['size'] ); ?>">
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('縦の長さ(height):'); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" placeholder="デフォルト:400" value="<?php echo esc_attr( $instance['height'] ); ?>" size="3">
    </p>
    <?php
  }

  public function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['size'] = strip_tags($new_instance['size']);
    $instance['height'] = strip_tags($new_instance['height']);
    return $instance;
  }

  //ウィジェットの表示
  public function widget($args, $instance){

    echo $args['before_widget'];

    echo $args['before_title'];

    echo "<p style='text-align:center;";

    if($instance['size']){
      echo "font-size:".$instance['size']."px'>";
    }else{
      echo "font-size: 40px'>";
    }

    if($instance['title']){
      echo $instance['title']."</p>";
    }else{
      echo "年齢グラフ</p>";
    }

    if($instance['height']){
      if (is_numeric($instance['height'])) {
        $heightStyle = " height:".$instance['height'].'px;';
      } else {
        $heightStyle = " height:".$instance['height'].";";
      }
    }else{
      $heightStyle = " height:400px;";
    }

    echo $args['after_title'];

    echo "<div class='chart-container'><canvas id='stiPieChart' style='".$heightStyle."'></canvas></div>";

    echo $args['after_widget'];
  }
}

add_action(
	'widgets_init',
	function(){
		register_widget('StiWidget03'); //ウィジェットのクラス名を記述
	}
);

/* ##################################
    活動予定ウィジェット
  ################################## */

$sti_widget04 = new StiWidget04;

class StiWidget04 extends WP_Widget{

  //コンストラクタでウィジェットを登録
  function __construct(){
    parent::__construct(
    'StiWidget04',	//ウィジェットID
    'サバイバルチーム活動予定ウィジェット',		//ウィジェット名
    array('description' => 'サバイバルチームの活動予定を表示するウィジェットです。')	//ウィジェットの概要
    );

    global $wpdb;
    global $sti2;
    $scheduleSql = "SELECT * FROM " . $sti2->table_name." order by day asc";
    $this->scheduleResults = $wpdb->get_results($scheduleSql,"ARRAY_A");

  }

  public function form( $instance ) {
    ?>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" placeholder="デフォルト:活動予定" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>">
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('タイトルの大きさ(px):'); ?></label>
        <input type="number" class="widefat" id="<?php echo $this->get_field_id('size'); ?>" min=0 placeholder="デフォルト:40" name="<?php echo $this->get_field_name('size'); ?>" value="<?php echo esc_attr( $instance['size'] ); ?>">
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('横の長さ(width):(cssでwidthを指定するので、autoなどにされても構いません。(px))'); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" placeholder="デフォルト:特に指定しません" value="<?php echo esc_attr( $instance['width'] ); ?>" size="3">
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('show'); ?>"><?php _e('何個まで表示する?:'); ?></label>
        <input type="number" id="<?php echo $this->get_field_id('show'); ?>" min=0 name="<?php echo $this->get_field_name('show'); ?>" placeholder="デフォルト:5" value="<?php echo esc_attr( $instance['show'] ); ?>" size="3">
    </p>
    <?php
  }

  public function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['size'] = strip_tags($new_instance['size']);
    $instance['width'] = strip_tags($new_instance['width']);
    $instance['show'] = strip_tags($new_instance['show']);
    return $instance;
  }

  //ウィジェットの表示
  public function widget($args, $instance){

    if($instance['width']){
      if (is_numeric($instance['width'])) {
        $widthStyle = " style=width:".$instance['width'].'px';
      } else {
        $widthStyle = " style=width:".$instance['width'];
      }
    }

    echo $args['before_widget'];

    echo $args['before_title'];

    echo "<p style='text-align:center;";

    if($instance['size']){
      echo "font-size:".$instance['size']."px'>";
    }else{
      echo "font-size: 40px'>";
    }

    if($instance['title']){
      echo $instance['title']."</p>";
    }else{
      echo "活動予定</p>";
    }

    echo $args['after_title'];

    echo "<div class='page foldtl'".$widthStyle.">
    <div class='space'></div>

      <table id='kiloji_b'>
      <tbody>
      <tr>
        <th class='a'>開催日</th>
        <th class='a'>開催フィールド</th>
        <th class='a'>参加費</th>
      </tr>";

    if(!$instance['show']){
      $instance['show'] = 5;
    }

    $needNum = min(array(count($this->scheduleResults),$instance['show']));

    for($i=0;$i<$needNum;$i++){
        echo '<tr>';
        echo '<th class="a">'.$this->scheduleResults[$i]['day'].'</th>';
        echo '<th class="a">'.$this->scheduleResults[$i]['field'].'</th>';
        echo '<th class="a">'.number_format($this->scheduleResults[$i]['fee']).'円</th>';
        // echo '<th class="a">'.$this->scheduleResults[$i]->people.'</th>';
        echo '</tr>';
    }
    echo "</tbody>
    </table>
    </div>";

    echo $args['after_widget'];
  }
}

add_action(
	'widgets_init',
	function(){
		register_widget('StiWidget04'); //ウィジェットのクラス名を記述
	}
);
