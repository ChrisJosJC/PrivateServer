<?php  
session_start();

require 'config/config.php';
require 'funcs/conexion.php';
require 'funcs/funcs.php';
require 'funcs/files.php';

if (!validateSession()) {

	echo '<meta http-equiv="refresh" content="0; url='.SERVERURL.'index.php">';
	die();
} 

$index = "root";

$filesUser = getFiles($index);
$foldersUser = getFolder($index);
$AllFiles = getAllFiles($index);
$AllFolders = getAllFolders($index);

if (isset($_POST['AddFile'])) {
		
	if ($_FILES) {
			
		$file = $_FILES;

		addFile($file, $index);
			
	}
}

if (isset($_POST['CreateFolder'])) {

	$nameFolder =  $mysqli->real_escape_string($_POST['folder']);
		
	createFolder($nameFolder, $index);
	?>
	<script defer>
	console.log('Probando');
	appendAlert('Your folder has been created succesfully!', 'success')</script>
<?php
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="google" content="notranslate">
	<meta name="format-detection" content="telephone=no"/>
	<meta name="theme-color" content="<?php echo THEMECOLOR ?>">
	<meta name="description" content="<?php echo APPDESCRIPTION ?>">
	<meta name="og:description" content="<?php echo APPDESCRIPTION ?>"/>
	<meta name="og:url" content="<?php echo SERVERURL ?>"/>
	<meta name="og:title" content="<?php echo APPTITLE ?>"/>
	<meta name="og:image" content="<?php echo SERVERURL ?>img/private_server.png"/>
	<!--	ICONS PAGE	-->
		<link id="favicon" rel="shortcut icon" href="<?php echo SERVERURL ?>favicon.svg" >

	<link rel="apple-touch-icon" sizes="194x194" href="<?php echo SERVERURL ?>img/apple-touch-icon.png" type="image/png"/>
	<!--    NORMALIZE.CSS v8.0.1    -->
	<link rel="stylesheet" href="<?php echo SERVERURL ?>css/normalize.css">
	<!--    CUSTOM CSS    -->
	<link rel="stylesheet" href="<?php echo SERVERURL ?>css/elements.css">
	<link rel="stylesheet" href="<?php echo SERVERURL ?>css/scrollbar.css">
	<link rel="stylesheet" href="<?php echo SERVERURL ?>css/home.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
	<!--	ICONS fontawesome-free	-->
	<link rel="stylesheet" href="<?php echo SERVERURL ?>plugins/fontawesome-free/css/all.min.css">
	<!--    SCRIPT JS    --->
	<script src="<?php echo SERVERURL ?>js/script.js"></script>
	<title><?php echo TITLEHOME ?></title>
	<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body >

	<input type="checkbox" name="btnnavbar" id="btnnavbar">

	<aside class="navbar">
		<nav>
			<ul>
				<?php if ($_SESSION['id_tipo'] == 3) { ?>
					<li><a href="<?php echo SERVERURL; ?>home.php" class="navActive"><i class="far fa-file"></i><span>My files</span></a></li>
					<li><a href="<?php echo SERVERURL; ?>exit.php"><i class="fas fa-sign-out-alt"></i><span>Exit</span></a></li>
				<?php } ?>

				<?php if ($_SESSION['id_tipo'] == 1 || $_SESSION['id_tipo'] == 2) { ?>

				<li><a href="<?php echo SERVERURL; ?>home.php" class="navActive"><i class="far fa-file"></i><span>My files</span></a></li>
				<li><a href="<?php echo SERVERURL; ?>user/account.php"><i class="far fa-user"></i><span>Account</span></a></li>
				<?php if ($_SESSION['id_tipo'] == 1) { ?>
				<li><a href="<?php echo SERVERURL; ?>register.php"><i class="fas fa-user-plus"></i><span>Register User</span></a></li>
				<li><a href="<?php echo SERVERURL; ?>user/users.php"><i class="fas fa-users"></i><span>Users</span></a></li>

				<?php } ?>
				<li class="settings"><a href="<?php echo SERVERURL; ?>user/setting.php"><i class="fas fa-cog"></i><span>Settings</span></a></li>
				<li><a href="<?php echo SERVERURL; ?>exit.php"><i class="fas fa-sign-out-alt"></i><span>Exit</span></a></li>

				<?php } ?>

			</ul>
		</nav>
	</aside>

	<input type="checkbox" name="btnuserImage" id="btnuserImage">
	
	<section class="header">
		<header><label for="btnnavbar"><i class="fas fa-bars"></i></label><span>&nbsp;Records <strong>Buffets<i class="fas fa-lock"></i></strong></span></header>
		
		<div class="userImage">
			<label for="btnuserImage" id="labeluserImage">
				<?php echo subUser($_SESSION['name']) ; ?>
			</label>
			<img src="<?php echo SERVERURL.$_SESSION['image']?>">
			<div class="userImage-options">
				<p><a href="<?php echo SERVERURL; ?>user/account.php"><span><?php echo $_SESSION['email']; ?></span><i class="fas fa-envelope"></i></a></p>
				<p><a href="<?php echo SERVERURL; ?>user/setting.php"><span>Settings</span><i class="fas fa-cog"></i></a></p>
				<p><a href="<?php echo SERVERURL; ?>exit.php"><span>Exit</span><i class="fas fa-sign-out-alt"></i></a></p>
			</div>
		</div>
	</section>

	<?php if ($_SESSION['id_tipo'] != 3){ ?>
		<section class="operations">
		<input type="checkbox" name="btnaddFile" id="btnaddFile">
		<div class="operations-item">
			<label for="btnaddFile" title="Add File">
				<i class="fas fa-plus-circle"></i>
			</label>
			<div class="inputAddFile">
				<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="fileForm">
					<label for="btnaddFile">✖</label>
					<p class="title-login"><i class="fas fa-file"></i>Add File</p>		
					<input type="file" name="file" id="file" onchange="cambiar()" required>
					<label for="file" id="info">Select File</label><br>
					<input type="submit" value="Send File" name="AddFile">
				</form>
			</div>
		</div>
		<input type="checkbox" name="btnnewFolder" id="btnnewFolder">
		<div class="operations-item">
			<label for="btnnewFolder" title="New Folder">
				<i class="fas fa-folder-plus"></i>
			</label>
			<div class="inputNewFolder">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<label for="btnnewFolder">✖</label>
					<p class="title-login"><i class="fas fa-folder"></i>New folder</p>
					<input type="text" name="folder" id="folder" required>
					<input type="submit" value="Create Folder" name="CreateFolder">
				</form>	
			</div>	
		</div>
	</section>
	<?php } ?>
	

	<section class="files">
		<?php if ($_SESSION['id_tipo'] != 2){ ?>
			<?php if ($AllFolders != null){ ?>
			<?php foreach ($AllFolders as $folder ){ ?>

				<?php if ($folder['name'] != "root") { ?>
					<article class="cardfolder">
					<a href="<?php echo SERVERURL;?>page.php?cid=<?php echo $folder['cid']?>" class="link">
						<div class="img">
							<img src="<?php echo SERVERURL;?>images/folder.png">
						</div>
						<div class="name">
							<span><?php echo subname($folder['name']); ?></span>
							<div class="files-options">
								<a href="<?php echo SERVERURL;?>service/downloadFolder.php?cid=<?php echo $folder['cid']?>"><i class="fas fa-download"></i></a>
								<?php if ($_SESSION['id_tipo']!=3) { ?>
									<a href="<?php echo SERVERURL;?>service/deleteFolder.php?cid=<?php echo $folder['cid']?>"><i class="far fa-trash-alt"></i></a>
								<?php } ?>
							</div>
						</div>
					</a>
				</article>	

			<?php }}} ?>
			<?php if ($filesUser != null){ ?>
			<?php foreach ($filesUser as $file ){ ?>
				<div class="cardfile">
					<a href="<?php echo SERVERURL;?>service/view.php?type=<?php echo $file['extension'] ?>&cid=<?php echo $file['cid']  ?>" class="link" title="<?php echo $file['name']?>">
						<div class="img">
							<img src="<?php echo SERVERURL;?>images/<?php echo $file['extension'] ?>.png">
						</div>
						<div class="name">
							<span><?php echo subname($file['name'])  ?></span>
							<div class="files-options">
								<a href="<?php echo SERVERURL;?>service/download.php?type=<?php echo $file['extension'] ?>&cid=<?php echo $file['cid']  ?>"><i class="fas fa-download"></i></a>
								
								<?php if ($_SESSION['id_tipo']!=3) { ?>
									<a href="<?php echo SERVERURL;?>service/delete.php?type=<?php echo $file['extension'] ?>&cid=<?php echo $file['cid']  ?>"><i class="far fa-trash-alt"></i></a>
								<?php } ?>
							</div>
						</div>
					</a>
				</div>
			<?php } ?>
		<?php }} ?>	

		<?php if ($_SESSION['id_tipo'] == 2){ ?>
			<?php if ($foldersUser != null){ ?>
			<?php foreach ($foldersUser as $folder ){ ?>

				<?php if ($folder['name'] != "root") { ?>
					<article class="cardfolder">
					<a href="<?php echo SERVERURL;?>page.php?cid=<?php echo $folder['cid']?>" class="link">
						<div class="img">
							<img src="<?php echo SERVERURL;?>images/folder.png">
						</div>
						<div class="name">
							<span><?php echo subname($folder['name']); ?></span>
							<div class="files-options">
								<a href="<?php echo SERVERURL;?>service/downloadFolder.php?cid=<?php echo $folder['cid']?>"><i class="fas fa-download"></i></a>
								<a href="<?php echo SERVERURL;?>service/deleteFolder.php?cid=<?php echo $folder['cid']?>"><i class="far fa-trash-alt"></i></a>
							</div>
						</div>
					</a>
				</article>	

		<?php }}} ?>
		</section>

		<?php if ($filesUser != null){ ?>
			<?php foreach ($filesUser as $file ){ ?>
				<div class="cardfile">
					<a href="<?php echo SERVERURL;?>service/view.php?type=<?php echo $file['extension'] ?>&cid=<?php echo $file['cid']  ?>" class="link" title="<?php echo $file['name']?>">
						<div class="img">
							<img src="<?php echo SERVERURL;?>images/<?php echo $file['extension'] ?>.png">
						</div>
						<div class="name">
							<span><?php echo subname($file['name'])  ?></span>
							<div class="files-options">
								<a href="<?php echo SERVERURL;?>service/download.php?type=<?php echo $file['extension'] ?>&cid=<?php echo $file['cid']  ?>"><i class="fas fa-download"></i></a>
								<a href="<?php echo SERVERURL;?>service/delete.php?type=<?php echo $file['extension'] ?>&cid=<?php echo $file['cid']  ?>"><i class="far fa-trash-alt"></i></a>
							</div>
						</div>
					</a>
				</div>
			<?php } ?>
		<?php }} ?>	
		
	</section> 
	<footer>
		<a href="<?php echo MYWEB ?>" target="_BLANK">
			<i>Buffets Records</i>&nbsp;&copy;
		</a>
	</footer>
</div>

<div id="liveAlertPlaceholder"></div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>