<?php

$cleanup = function($text) {
	return preg_replace('/\n\s+-\s/msi', "\n\n - ", $text);
};

?>
<div class="nav" class="<?=$object['type']; ?>">
	<span class="type"><?=$object['type']; ?></span>
	<?php
		$path = array_filter(array_merge(
			array($object['name']), explode('\\', $object['identifier'])
		));
		$url = '';
		$curPath = str_replace('\\', '/', $name);

		foreach (array_slice($path, 0, -1) as $part) {
			$url .= '/' . $part;
			echo '<h3>' . $this->html->link($part, 'docs' . $url) . '</h3> \ ';
		}
		$ident = end($path);

		if (strpos($ident, '::') !== false) {
			list($class, $ident) = explode('::', $ident, 2);
			echo '<h3>' . $this->html->link($class, "docs{$url}/{$class}") . '</h3> :: ';
			echo $h($ident);
		} else {
			echo '<h3>' . $h($ident) . '</h3>';
		}
	?>
</div>

<?php if ($object['children']) { ?>
	<h4>Package contents</h4>
	<ul class="children">
		<?php foreach ($object['children'] as $class => $type) { ?>
			<?php
				$parts = explode('\\', $class);
				$url = 'docs/' . str_replace('\\', '/', $class);
			?>
			<li class="<?=$type; ?>"><?php echo $this->html->link(end($parts), $url); ?></li>
		<?php } ?>
	</ul>
<?php } ?>

<?php // Parent object ?>
<?php if ($object['parent']) { ?>
	<?php $parent = $object['parent']; ?>
	<h4>Parent class</h4>
	<span class="parent">
		<?php echo $this->html->link($parent, 'docs/' . str_replace('\\', '/', $parent)); ?>
	</span>
<?php } ?>

<?php if ($object['info']['description']) { ?>
	<h4>Description</h4>
	<p class="description wiki-text"><?=$cleanup($object['info']['description']); ?></p>

	<?php if (!empty($object['info']['text'])) { ?>
		<p class="text wiki-text"><?=$object['info']['text']; ?></p>
	<?php } ?>
<?php } ?>

<?php // Method parameters ?>
<?php if (isset($object['info']['tags']['params'])) { ?>
	<h4>Parameters</h4>
	<ul class="parameters">
		<?php foreach ($object['info']['tags']['params'] as $name => $data) { ?>
			<li>
				<span class="type"><?=$data['type']; ?></span>
				<?=$name; ?>
				<span class="description wiki-text"><?=$cleanup($data['text']); ?></span>
			</li>
		<?php } ?>
	</ul>
<?php } ?>

<?php // Method return value ?>
<?php if (isset($object['info']['return'])) { ?>
	<h4>Returns</h4>
	<span class="type"><?=$object['info']['return']['type']; ?></span>
	<span class="return wiki-text"><?=$cleanup($object['info']['return']['text']); ?></span>
<?php } ?>

<?php // Method filtering info ?>
<?php if (isset($object['info']['tags']['filter'])) { ?>
	<span class="flag wiki-text">
		This method can be filtered.
	</span>
<?php } ?>

<?php // Related items ?>
<?php if (isset($object['info']['tags']['see'])) { ?>
	<h4>Related</h4>
	<ul class="related">
		<?php foreach ((array)$object['info']['tags']['see'] as $name) { ?>
			<li><?php echo$this->html->link($name, 'docs/' . str_replace('\\', '/', $name)); ?></li>
		<?php } ?>
	</ul>
<?php } ?>

<?php // Object properties ?>
<?php if ($object['properties']) { ?>
	<h4>Properties</h4>
	<ul class="properties">
		<?php foreach ($object['properties'] as $name => $value) { ?>
			<li><?php echo $this->html->link($name, "docs/{$curPath}::\${$name}"); ?></li>
		<?php } ?>
	</ul>
<?php } ?>

<?php // Object methods ?>
<?php if ($object['methods'] && $object['methods']->count()) { ?>
	<h4>Methods</h4>
	<ul class="methods">
		<?php foreach ($object['methods'] as $method) { ?>
			<?php $url = "docs/{$curPath}::{$method->name}()"; ?>
			<li><?php echo $this->html->link($method->name, $url); ?></li>
		<?php } ?>
	</ul>
<?php } ?>

<?php // Object subclasses ?>
<?php if ($object['subClasses']) { ?>
	<h4>Subclasses</h4>
	<ul class="subclasses">
		<?php foreach ($object['subClasses'] as $class) { ?>
			<?php $url = 'docs/' . str_replace('\\', '/', $class); ?>
			<li><?php echo $this->html->link($class, $url); ?></li>
		<?php } ?>
	</ul>
<?php } ?>
