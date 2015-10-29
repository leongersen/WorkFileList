<!DOCTYPE html>
<style>
html { font: 400 14px/24px 'Segoe UI'; -webkit-user-select: none; }
label { display: block; position: relative; padding-left: 20px }
input { position: absolute; left: 0; }
div { width: 50px; }
.view label input:not(:checked) + span { display: none; }
.view input { left: -999px }
.view span { color: blue; text-decoration: underline; cursor: pointer }
</style>
<?php

	$op = $_GET['op'];
	$exclude = array('.git', 'node_modules');

	$filter = function ($file, $key, $iterator) use ($exclude) {
		if ($iterator->hasChildren() && !in_array($file->getFilename(), $exclude)) {
			return true;
		}
		return $file->isFile();
	};

	$innerIterator = new RecursiveDirectoryIterator($op, RecursiveDirectoryIterator::SKIP_DOTS);
	$filterIterator = new RecursiveCallbackFilterIterator($innerIterator, $filter);
	$iterator = new RecursiveIteratorIterator($filterIterator);

	foreach ( $iterator as $path ) {
		if ( $path->isDir() ) continue;
		echo '<label><input type="checkbox"><span>' . substr($path->__toString(), strlen($op)) . '</span></label>';
	}

?>
<script>

	var op = '<?php echo addslashes($op); ?>';

	window.$ = document.querySelectorAll.bind(document);

	Node.prototype.on = window.on = function (name, fn) {
		this.addEventListener(name, fn);
	};

	NodeList.prototype.forEach = Array.prototype.forEach;

	NodeList.prototype.on = function (name, fn) {
		this.forEach(function(elem) {
			elem.addEventListener(name, fn, false);
		});
	};
	NodeList.prototype.off = function (name, fn) {
		this.forEach(function(elem) {
			elem.removeEventListener(name, fn, false);
		});
	};

	function prevUntil(element, target) {
		var result = [];
		while ( (element = element.previousElementSibling) && element != target ) {
			result.push(element);
		}
		return result;
	}

	function nextUntil(element, target) {
		var result = [];
		while ( (element = element.nextElementSibling) && element != target ) {
			result.push(element);
		}
		return result;
	}

	function isBefore ( a, b ) {
		return prevUntil(b, false).indexOf(a) === -1;
	}

	function setBox ( el ) {
		el.children[0].checked = STATE;
	}

	function handler ( e ) {
		if ( !e.buttons ) return;

		var input = this.children[0];
		input.checked = STATE;

		if ( this != STARTER ) {
			(isBefore(this, STARTER) ? prevUntil : nextUntil)(this, STARTER).forEach(setBox)
		}

		this.removeEventListener('mousemove', handler);
		return false;
	}

	var STATE = true, STARTER = false;

	document.on('mouseup', function(){
		$('label').off('mousemove', handler);
	});

	$('label').on('mousedown', function(e){
		if ( document.body.classList.contains('view') ) {
			return false;
		}
		this.children[0].checked = STATE = !this.children[0].checked;
		STARTER = this;

		$('label').on('mousemove', handler);
	});

	$('label').on('click', function(e){
		if ( document.body.classList.contains('view') ) {
			window.location.href = 'plusplus:' + op + this.children[1].innerText;
			console.log('plusplus:' + op + this.children[1].innerText);
		}
		e.preventDefault();
		return false;
	});

	document.on('keydown', function(e){
		if ( e.keyCode !== 65 ) return;
		document.body.classList.toggle('view');
	});

</script>
