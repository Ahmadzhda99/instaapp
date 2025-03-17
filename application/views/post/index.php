<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Halaman POst</title>
</head>
<body>
	<?php foreach ($posts as $post): ?>
		<p><?= $post['username'] ?>: <?= $post['caption'] ?></p>
		<img src="<?= base_url('uploads/'.$post['image']) ?>" width="300">

		<!-- Tombol Like -->
		<form action="<?= site_url('post/like/'.$post['id']) ?>" method="post">
			<button type="submit">
				<?= $this->Like_model->user_liked($this->session->userdata('user_id'), $post['id']) ? 'Unlike' : 'Like' ?>
			</button>
		</form>
		<p>Likes: <?= $this->Like_model->get_likes_count($post['id']) ?></p>

		<!-- Komentar -->
		<form action="<?= site_url('post/comment/'.$post['id']) ?>" method="post">
			<input type="text" name="comment" placeholder="Tambah komentar">
			<button type="submit">Kirim</button>
		</form>
		<ul>
			<?php foreach ($this->Comment_model->get_comments($post['id']) as $comment): ?>
				<li><?= $comment['username'] ?>: <?= $comment['comment'] ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>
</body>
</html>