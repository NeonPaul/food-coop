<h2>Updating the catalogue</h2>
<p>To update the catalogue you'll need to use spreadsheet software such as Microsoft Excel or <a href="http://www.openoffice.org/" target="openoffice">Open Office Calc.</a></p>

<ol>
<li><strong>Download</strong> the latest catalogue, in <b>XLS format</b>, from the Infinity Foods website: <a href="http://www.infinityfoodswholesale.co.uk/catalogue/" target="_blank">Click here</a>.
<li><strong>Open</strong> the file using your spreadsheet software and <strong>save it in CSV format</strong>.
<li>Use the form below to <strong>upload</strong> the new CSV file.
</ol>

<?if(isset($error)) echo "<b><div class='errors'>$error</div></b>";?>
<?if(isset($success)) echo "<b><div style='color:green'>$success</div></b>";?>

<?=form_open_multipart("admin/catalogue");?>
<?=form_upload('file');?>
<?=form_submit('upload','Upload file');?>
</form>