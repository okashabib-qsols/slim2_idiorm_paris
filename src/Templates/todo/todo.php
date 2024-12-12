<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
	<link rel="stylesheet" href="/styles/style.css">
	<title>
		<?php echo $title; ?>
	</title>
</head>

<body>
	<div id="page-wrap">
		<h1>Todo App</h1>

		<div id="overlay"></div>

		<!-- Loader -->
		<div id="loader" role="status">
			<img src="/images/loader.gif" width="50" height="50" alt="Loading...">
			<span class="sr-only">Loading...</span>
		</div>

		<div id="main">
			<ul id="list" class="ui-sortable">
				<?php if (empty($data)): ?>
					<li class="bad">No Data Found</li>
				<?php else: ?>
					<?php foreach ($data as $todo): ?>
						<li class="<?php echo ($todo['color'] == 1 ? 'color-blue' : 'color-' . htmlspecialchars($todo['color'])); ?> <?php echo ($todo['is_done'] == 1 ? 'completed' : ''); ?> list"
							id="todo_<?php echo htmlspecialchars($todo['id']); ?>">
							<span id="<?php echo htmlspecialchars($todo['id']); ?>listitem" title="Double-click to edit..."
								style="background-color: <?php echo ($todo['color'] != 1 ? htmlspecialchars($todo['color']) : ''); ?>">
								<?php echo htmlspecialchars($todo['description']); ?>
								<?php if ($todo['is_done'] == 1): ?>
									<img src="/images/crossout.png" class="crossout" alt="Completed" />
								<?php endif; ?>
							</span>
							<button class="draggertab tab" aria-label="Drag Todo"></button>
							<button class="colortab tab" aria-label="Change Color"></button>
							<button class="deletetab tab" aria-label="Delete Todo"></button>
							<button class="donetab tab" aria-label="Mark as Done"></button>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>

			<form id="add-new" method="post">
				<input type="hidden" id="csrfToken" name="csrf_token" value=<?php echo $_SESSION['csrf_token']; ?> />
				<input type="text" id="description" placeholder="New Todo" name="description" />
				<button type="submit" id="add-new-submit" class="button">Add</button>
			</form>
		</div>
	</div>
	<noscript>This site just doesn't work, period, without JavaScript</noscript>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" defer></script>
	<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js" defer></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js" defer></script>
	<script src="/js/app.js" defer></script>
</body>

</html>