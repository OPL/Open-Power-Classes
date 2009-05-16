<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en_US" xml:lang="en_US">
<head>
	<title>Paginator Test 1</title>
</head>
<body>
<p>
{$pager::decorator is 'all'}
<opt:selector name="pager" test="type">
	<opt:number> <a parse:href="'?page='~$pager.number">{$pager.number}</a> </opt:number>
	<opt:current> <strong>[{$pager.number}]</strong> </opt:current> 
</opt:selector>
</p>
</body>
</html>
