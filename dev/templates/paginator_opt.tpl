<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en_US" xml:lang="en_US">
<head>
	<title>Paginator Test 1</title>
</head>
<body>
<p>Page {$pager.page} of {$pager.pageCount}</p>

<p>
{$pager::decorator is 'all'}
<opt:selector name="pager">
	<opt:number> <a parse:href="'?page='~$pager.number">{$pager.number}</a> </opt:number>
	<opt:current> <strong>[{$pager.number}]</strong> </opt:current> 
</opt:selector>
</p>

<p>
{$pager::decorator is 'slider'}
{$pager::decorator::chunk is 5}
<opt:selector name="pager">
	<opt:number> <a parse:href="'?page='~$pager.number">{$pager.number}</a> </opt:number>
	<opt:current> <strong>[{$pager.number}]</strong> </opt:current>
	<opt:gap> ... </opt:gap>
</opt:selector>
</p>

<p>
{$pager::decorator is 'stepping_slider'}
{$pager::decorator::chunk is 1}
{$pager::decorator::around is 1}
<opt:selector name="pager">
	<opt:number> <a parse:href="'?page='~$pager.number">{$pager.number}</a> </opt:number>
	<opt:current> <strong>[{$pager.number}]</strong> </opt:current>
	<opt:step> <a parse:href="'?page='~$pager.number"><small>{$pager.number}</small></a> </opt:step>
</opt:selector>
</p>

<p>
	<a parse:href="'?page='~$pager.first.number" opt:if="$pager.first.number">&lArr; first</a>
	<a parse:href="'?page='~$pager.previous.number" opt:if="$pager.previous.number">&larr; previous</a>
	<a parse:href="'?page='~$pager.next.number" opt:if="$pager.next.number">next &rarr;</a>
	<a parse:href="'?page='~$pager.last.number" opt:if="$pager.last.number">last &rArr;</a>
</p>
</body>
</html>
