<div style="text-align: center;background-color: white;clear: both;margin:0 18px" id="dv_edi">
    <div class="row">
            <div class="col-md-2">
                <input type="button" name="btnItalic" id="btnItalic" value="I" onclick="getItalic()" />
                <input type="button" name="btnBold" id="btnBold" value="B" onclick="getBold()" />
                <input type="button" name="btnUnderline" id="btnUnderline" value="U" onclick="getUnderline()" />
            </div>
            <div class="col-md-2">
                <b>Font Size</b><select style="width: 74%;display: inline;" name="ddlFS" id="ddlFS" class="form-select" onchange="getFS(this.value)">
                    <?php
                    for ($i = 1; $i <= 6; $i++) {
                    ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-5">
                <!--<img src="../images/download.jpg"  onclick="jus_cen()" style="width: 20px;height: 20px" />-->
                <input type="button" name="btnJustify" id="btnJustify" value="Center" onclick="jus_cen()" />
                <input type="button" name="btnAliLeft" id="btnAliLeft" value="Align Left" onclick="jus_left()" />
                <input type="button" name="btnAliRight" id="btnAliRight" value="Align Right" onclick="jus_right()" />
                <input type="button" name="btnFull" id="btnFull" value="Justify" onclick="jus_full()" />
                <input type="button" name="btnPrintable" id="btnPrintable" value="Print and Save" onclick="get_set_prt()" />
            </div>
            <div class="col-md-2">
                <select name="ddlFontFamily" id="ddlFontFamily" class="form-select" onchange="getFonts(this.value)">
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="'Kruti Dev 010'">Kruti Dev</option>
                </select>
            </div>
            <div class="col-md-1">
                <input type="button" name="btnIndent" id="btnIndent" value="Indent" onclick="get_intent()" />
            </div>
            <div class="col-md-2">
                <input type="button" name="btnsupScr" id="btnsupScr" value="Superscript" onclick="get_supScr()" />
                <input type="button" name="txtRedo" id="txtRedo" onclick="gt_redo()" value="Redo" />
            </div>
            <div class="col-md-3">
                <!-- <input type="button" name="btnFind" id="btnFind" onclick="fin_find()" value="Find"/>-->
                <input type="text" name="txtReplace" id="txtReplace" />
                
            </div>
            <div class="col-md-3">
            <input type="button" name="btnReplace" id="btnReplace" onclick="fin_rep()" value="Replace All" />
                <!--<input type="button" name="btnRePrint" id="btnRePrint" value="RePrint&Save" onclick="get_set_re_prt()"/>-->
                <input type="button" name="btn_sign" id="btn_sign" value="Sign" onclick="sign()" style="display:none" />
                <input type="button" name="btn_publish" id="btn_publish" value="Publish" onclick="publish_record()" />
                <!-- input type="button" name="btn_draft_pnt" id="btn_draft_pnt" value="Save Draft"  onclick="draft_record()"/ -->
                <input type="button" name="btn_prnt" id="btn__prnt" value="Print" onclick="draft_record1()" />
            </div>

    </div>
</div>