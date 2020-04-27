<div>
<h1>隊員管理画面</h1>
<?php echo $a; ?>
<?php echo $b; ?>
<?php echo $c; ?>
<form method="post" action="" class="form-horizontal">
  <?php echo $nonce; ?>
  <input type="hidden" name="created" value="1">
  <h2>隊員追加</h2>
  <fieldset>
    <div id="control-group-1" class="control-group">
      <label class="control-label" for="name">隊員名</label>
      <div class="controls">
        <input required type="text" name="sti_name" id="name" value="">
      </div>
    </div>
    <div id="sti_1">
      <div id="control-group-2" class="control-group">
        <label class="control-label" for="posion">役職</label>
        <div class="controls">
          <select name="sti_posion" id="posion">
          <?php echo $box; ?>
          </select>
        </div>
      </div>
      <div id="control-group-3" class="control-group">
        <label class="control-label" for="age">年齢</label>
        <div class="controls">
          <select name="sti_age" id="age">
            <option value="">選択してください(空欄可)</option>
            <option value="10s">20歳未満</option>
            <option value="20s">20-29歳</option>
            <option value="30s">30-39歳</option>
            <option value="40s">40-49歳</option>
            <option value="50s">50-59歳</option>
            <option value="60s">60-69歳</option>
            <option value="70s">70-79歳</option>
            <option value="80s">80歳以上</option>
        </select>
        </div>
      </div>
      <div id="control-group-4" class="control-group">
        <label class="control-label" for="sex">性別</label>
        <div class="controls">
          <select name="sti_sex" id="sex">
            <option value="">選択してください(空欄可)</option>
            <option value="male">男性</option>
            <option value="female">女性</option>
          </select>
        </div>
      </div>
      <div id="control-group-5" class="control-group">
        <label class="control-label" for="image">プロフィール画像</label>
        <div class="controls">
            <input type="hidden" name="sti_image" id="image" value="">
            <a class="button upload_image_button">画像をアップロードする</a>
        </div>
      </div>
    </div>
    <div id="sti_2">
      <div id="control-group-6" class="control-group">
        <label class="control-label" for="comment">コメント</label>
        <div class="controls">
            <textarea type="text" name="sti_comment" id="comment" value=""></textarea>
        </div>
      </div>
      <div id="control-group-7" class="control-group">
        <button type="submit" class="submit button" id="sti_submit">送信</button>
      </div>
    </div>
    <div id="notice2"></div>
    <div id="sti_3">
      <div id="control-group-8" class="control-group">
        <label class="control-label" for="posion_add">役職追加</label>
        <div class="controls">
          <input type="text" id="posion_add" value="">
          <a class="button" id ="posion_add_button">追加</a>
        </div>
      </div>
      <div id="control-group-9" class="control-group">
        <label class="control-label" for="posion_remove">役職削除</label>
        <div class="controls">
          <select id="posion_remove">
          <?php echo $box2; ?>
          </select>
          <a class="button" id ="posion_remove_button">削除</a>
        </div>
      </div>
    </div>
  </fieldset>
  <div class="scroll">
    <h2>隊員状況</h2>
    <div id="notice"></div>
    <table class="wp-list-table widefat ">
      <tbody id="sortable">
        <tr>
          <th class="day">入隊日</th>
          <th class="fix">固定</th>
          <th class="honorific">敬称</th>
          <th class="name">名前</th>
          <th class="allText"></th>
          <th class="posion">役職</th>
          <th class="sex">性別</th>
          <th class="age">年齢</th>
          <th class="profile">プロフィール画像</th>
          <th class="comment">コメント</th>
          <th class="allText"></th>
          <th class="sti_edit">編集</th>
          <th class="sti_delete">削除</th>
        </tr>
        <?php foreach ($sti_sql_result as $key=>$val) : ?>
        <tr>
          <td class="day sort" data-id="<?php echo $val->id?>"><?php echo $val->created ?></td>
          <td class="fix"><input name="sti_fix" type="checkbox" value="1" class="fix" data-id="<?php echo $val->id;?>" <?php is_checked('fix',$val->id) ?>></td>
          <td class="honorific"><input name="sti_honorific" type="checkbox" value="1" class="honorific" data-id="<?php echo $val->id;?>" <?php is_checked('honorific',$val->id) ?>></td>
          <td class="name over-text"><?php echo $val->name ?></td>
          <td class="allText"><a class='text'>全文表示</a><p class='fukidashi'><?php echo $val->name?></p>
          <p class='fukidashi'><?php echo $val->name?></p></td>
          <td class="posion"><?php echo $val->posion ?></td>
          <td class="sex"><?php echo $val->jpn_sex ?></td>
          <td class="age"><?php echo $val->jpn_age ?></td>
          <td class="profile"><?php if($val->image !== ""){echo "アップロード済みです <a href='".$val->image."'>表示する</a>";} ?></td>
          <td class="comment over-text"><?php echo $val->comment?></td>
          <td class="allText"><a class='text' >全文表示</a><p class='fukidashi'><?php echo $val->comment?></p></td>
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
</div>
<div class="modal js-modal">
    <div class="modal__bg js-modal-close"></div>
    <div class="modal__content">
      <form method="post" action="" class="form-horizontal" id="modal-form">
        <?php echo $nonce; ?>
        <input type="hidden" name="sti_edit_id" id="modal_id" value="">
        <h2>隊員情報編集</h2>
        <fieldset>
          <div id="sti_0">
            <div id="control-group-1" class="modal-control-group">
              <label class="control-label" for="name">隊員名</label>
              <div class="controls">
                <input required type="text" name="sti_edit_name" id="modal_name" value="">
              </div>
            </div>
            <div id="control-group-0" class="modal-control-group">
              <label class="control-label" for="name">入隊日</label>
              <div class="controls">
                <input required type="date" name="sti_edit_day" id="modal_day" value="">
              </div>
            </div>
          </div>
          <div id="sti_1">
            <div id="control-group-2" class="modal-control-group">
              <label class="control-label" for="posion">役職</label>
              <div class="controls">
                <select name="sti_edit_posion" id="modal_posion">
                <?php echo $box; ?>
                </select>
              </div>
            </div>
            <div id="control-group-3" class="modal-control-group">
              <label class="control-label" for="age">年齢</label>
              <div class="controls">
                <select name="sti_edit_age" id="modal_age">
                  <option value="">選択してください(空欄可)</option>
                  <option value="10s">20歳未満</option>
                  <option value="20s">20-29歳</option>
                  <option value="30s">30-39歳</option>
                  <option value="40s">40-49歳</option>
                  <option value="50s">50-59歳</option>
                  <option value="60s">60-69歳</option>
                  <option value="70s">70-79歳</option>
                  <option value="80s">80歳以上</option>
              </select>
              </div>
            </div>
            <div id="control-group-4" class="modal-control-group">
              <label class="control-label" for="sex">性別</label>
              <div class="controls">
                <select name="sti_edit_sex" id="modal_sex">
                  <option value="">選択してください(空欄可)</option>
                  <option value="male">男性</option>
                  <option value="female">女性</option>
                </select>
              </div>
            </div>
            <div id="control-group-5" class="modal-control-group">
              <label class="control-label" for="modal_image">プロフィール画像</label>
              <div class="controls">
                  <input type="hidden" name="sti_edit_image" id="modal_image" value="">
                  <a class="button upload_image_button" id="modal_image_button">画像をアップロードする</a>
              </div>
            </div>
          </div>
            <div id="control-group-6" class="control-group">
              <label class="control-label" for="modal_comment">コメント</label>
              <div class="controls">
                  <textarea type="text" name="sti_edit_comment" id="modal_comment" value="">
                  </textarea>
              </div>
            </div>
            <div id="control-group-5" class="modal-control-group">
              <button type="submit" name="edit" value="1" class="submit button" id="sti_modal_submit">送信</button>
            </div>
        </fieldset>
      </form>
        <a class="js-modal-close" href="">閉じる</a>
    </div><!--modal__inner-->
</div><!--modal-->


<?php
return false;
