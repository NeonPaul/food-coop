<div class="container">
	<h1>Constitution</h1>
	<p>The constitution is the guiding document for the co-op. In order to be a member you need to read and agree to it.</p>
	<blockquote id="constitution">
	<?=$constitution;?>
	</blockquote>

	<?=form_open("settings/constitution");?>
	<div class="clearfix">
		<label for="agree">
		<input type="checkbox" name="agree" id="agree" value="1"<?if($agreed){echo " CHECKED";}?>>I agree.</label>
	</div>
	<input type="submit" name="submit" value="Continue" class="btn">
	</form>
</div>