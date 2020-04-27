<div>
<h1>サバゲースケジュール管理</h1>
<?php echo $a; ?>
<?php echo $b; ?>
<?php echo $c; ?>
<h2>日程追加</h2>
<form method="post" action="" class="form-horizontal">
  <?php echo $nonce; ?>
  <input type="hidden" name="created" value="1">
  <fieldset>
    <div id="sti_1">
      <div id="control-group-1" class="control-group">
        <label class="control-label" for="day">開催日</label>
        <div class="controls">
          <input required type="date" min="<?php echo date('Y-m-d')?>" name="sti_day" id="day" value="">
        </div>
      </div>
      <div id="control-group-2" class="control-group">
        <label class="control-label" for="field">開催フィールド</label>
        <div class="controls">
          <input required type="text" placeholder="ドコソコフィールド" name="sti_field" id="field" value="">
          </select>
        </div>
      </div>
      <div id="control-group-3" class="control-group">
        <label class="control-label" for="fee">参加費</label>
        <div class="controls">
          <input required type="number" placeholder="例:1000" min=0 name="sti_fee" id="fee" value=""><span style="font-size:20px;"> 円</span>
        </div>
      </div>
      <div id="control-group-4" class="control-group">
        <div class="controls">
        <button type="submit" class="submit button" id="sti_submit">送信</button>
      </div>
      </div>
    </div>
  </fieldset>

  <div class="scroll">
    <h2>サバゲー日程</h2>
    <table class="wp-list-table widefat ">
      <tbody>
        <tr>
          <th class="day">開催日</th>
          <th>開催フィールド</th>
          <th>参加費</th>
          <!-- <th>参加人数(予定)</th> -->
          <th class="sti_edit">編集</th>
          <th class="sti_delete">削除</th>
        </tr>
        <?php foreach ($sti_sql_result as $key=>$val) : ?>
        <tr>
          <td><?php echo $val->day ?></td>
          <td><?php echo $val->field ?></td>
          <td><?php echo number_format($val->fee)."円" ?></td>
          <!-- <td><?php echo $val->people ?></td> -->
          <td class="sti_edit">
            <a data-id="<?php echo $val->id ?>" class="button js-modal-open">編集する</a>
          </td>
          <td class="sti_delete">
            <a href="<?php echo $val->href_d ?>" class="button">削除する</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</form>
<div class="modal js-modal">
    <div class="modal__bg js-modal-close"></div>
    <div class="modal__content">
      <form method="post" action="" class="form-horizontal" id="modal-form">
        <?php echo $nonce; ?>
        <input type="hidden" name="sti_edit_id" id="modal_id" value="">
        <h2>スケジュール情報編集</h2>
        <fieldset>
            <div id="sti_1">
              <div id="control-group-2" class="modal-control-group">
              <label class="control-label" for="modal_day">開催日</label>
              <div class="controls">
                <input required type="date" min="<?php echo date('Y-m-d')?>" name="sti_edit_day" id="modal_day" value="">
              </div>
            </div>
            <div id="control-group-2" class="modal-control-group">
              <label class="control-label" for="modal_field">開催フィールド</label>
              <div class="controls">
                <input required type="text" name="sti_edit_field" id="modal_field" value="">
              </div>
            </div>
            <div id="control-group-3" class="modal-control-group">
              <label class="control-label" for="modal_fee">参加費</label>
              <div class="controls">
                <input required type="number" min=0 name="sti_edit_fee" id="modal_fee" value="">
              </div>
            </div>
          </div>
          <div id="control-group-4" class="modal-control-group">
            <button type="submit" name="edit" value="1" class="submit button" id="sti_modal_submit">送信</button>
          </div>
        </fieldset>
      </form>
        <a class="js-modal-close" href="">閉じる</a>
    </div><!--modal__inner-->
</div><!--modal-->

<?php return false;
?>
