<?php

/* ##################################
    隊員管理画面
  ################################## */

add_action( 'admin_enqueue_scripts', 'regist_sti_admin_styles' );

function regist_sti_admin_styles() {
  global $sti;
  if($sti->is_sti_admin()){

    wp_enqueue_media();

    wp_enqueue_style(
      'sti_admin_style',
      plugin_dir_url( STI_PLUGIN ) . 'style/admin_member.css?' . date("Hi", filemtime( plugin_dir_path( STI_PLUGIN ) . 'style/admin_member.css' ))
    );

    wp_enqueue_script(
      'sti_admin_js',
      plugin_dir_url( STI_PLUGIN ) . 'js/admin.js?' . date("Hi", filemtime( plugin_dir_path( STI_PLUGIN ) . 'js/admin.js' )),
      "",
      array('jquery','jquery-ui-sortable')
    );

    $path_arr = array(
      'plugin_dir_url' => plugin_dir_url( STI_PLUGIN ),
      'plugin_dir_path' => plugin_dir_path( STI_PLUGIN ),
      'home_url' => home_url(),
      'ajaxurl' => admin_url( 'admin-ajax.php')
    );

    wp_localize_script( 'sti_admin_js', 'path_arr', $path_arr
    );
  }
}


/* ##################################
    活動予定
  ################################## */

add_action( 'admin_enqueue_scripts', 'regist_sti_schedule_styles');

function regist_sti_schedule_styles() {
  global $sti2;
  if($sti2->is_sti_schedule()){
    wp_enqueue_style(
      'sti_admin_schedule_style',
      plugin_dir_url( STI_PLUGIN ) . 'style/admin_schedule.css?' . date("Hi", filemtime( plugin_dir_path( STI_PLUGIN ) . 'style/admin_schedule.css' ))
    );

    wp_enqueue_script(
      'sti_admin_js',
      plugin_dir_url( STI_PLUGIN ) . 'js/admin.js?' . date("Hi", filemtime( plugin_dir_path( STI_PLUGIN ) . 'js/admin.js' )),
      array('jquery'),
      false,
      true
    );

    $path_arr = array(
      'plugin_dir_url' => plugin_dir_url( STI_PLUGIN ),
      'plugin_dir_path' => plugin_dir_path( STI_PLUGIN ),
      'home_url' => home_url(),
      'ajaxurl' => admin_url( 'admin-ajax.php')
    );

    wp_localize_script( 'sti_admin_js', 'path_arr', $path_arr
    );

  }
}

/* ##################################
    ウィジェット
  ################################## */

add_action('wp_enqueue_scripts', 'sti_widget_enqueue_scripts');

function sti_widget_enqueue_scripts()
{
    // global $wp_scripts;
    // global $sti_widget01;
    global $sti_widget02;
    global $sti_widget03;

    // $ui = $wp_scripts->query('jquery-ui-core');
    //
    // wp_enqueue_style(
    //     'jquery-ui-smoothness',
    //     "//ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.min.css"
    //     // "//ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/ui-lightness/jquery-ui.min.css",
    // );

    wp_enqueue_style(
        'sti_wedget_css',
        plugin_dir_url( STI_PLUGIN ).'style/widget.css?' . date("Hi", filemtime( plugin_dir_path( STI_PLUGIN ) . 'style/widget.css' ))
        // "//ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/ui-lightness/jquery-ui.min.css",
    );

    wp_enqueue_style(
        'chart_css',
        plugin_dir_url( STI_PLUGIN ).'style/Chart.min.css'
    );

    wp_enqueue_style(
        'slick_css',
        plugin_dir_url( STI_PLUGIN ).'style/slick.css'
    );

    // wp_enqueue_style(
    //     'slick_theme_css',
    //     plugin_dir_url( STI_PLUGIN ).'style/slick-theme.css',
    // );

    wp_enqueue_script(
        'sti_widget_js',
        plugin_dir_url( STI_PLUGIN ).'js/widget.js?' . date("Hi", filemtime( plugin_dir_path( STI_PLUGIN ) . 'js/widget.js' )),
        array('jquery','chart_js','chart_Color_js','chart_datalabels_js','slick_js'),
        // array('jquery-ui-progressbar')
        false,
        true
    );

    $sti_widget_arr = array(
      // 'membersCount' => $sti_widget01->membersCount,
      // 'membersArr' => $sti_widget01->membersArr,
      'membersSexArr' => $sti_widget02->membersSexArr,
      'membersAgeArr' => $sti_widget03->membersAgeArr
    );

    wp_localize_script( 'sti_widget_js', 'sti_widget_arr', $sti_widget_arr
    );

    wp_enqueue_script(
        'chart_js',
        plugin_dir_url( STI_PLUGIN ).'js/Chart.min.js',
        array(),
        false,
        true
    );

    wp_enqueue_script(
        'chart_Color_js',
        plugin_dir_url( STI_PLUGIN ).'js/chartjs-plugin-colorschemes.min.js',
        array('chart_js'),
        false,
        true

    );

    wp_enqueue_script(
        'chart_datalabels_js',
        plugin_dir_url( STI_PLUGIN ).'js/chartjs-plugin-datalabels.min.js',
        array('chart_js'),
        false,
        true
    );

    wp_enqueue_script(
        'slick_js',
        plugin_dir_url( STI_PLUGIN ).'js/slick.min.js',
        array('jquery'),
        false,
        true
    );
}
