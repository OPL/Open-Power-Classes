<?php
// OPL Initialization
$config = parse_ini_file('../paths.ini', true);
require($config['libraries']['Opl'].'Base.php');
Opl_Loader::loadPaths($config);
Opl_Loader::setCheckFileExists(false);
Opl_Loader::register();
Opl_Registry::setState('opl_debug_console', false);
Opl_Registry::setState('opl_extended_errors', true);

try
{
	$opc = new Opc_Class;
	//$opc = new Opc_Class;

	$pager = Opc_Paginator::create(1000, 13); // returns Opc_Paginator_Pager;
	$pager->all = 1000;
	$pager->page = isset($_GET['page']) ? $_GET['page'] : 1;
	//$pager->offset = 174;
	
	
	echo "<ul>";
	foreach(new LimitIterator(new ArrayIterator(range(1, $pager->all)), $pager->offset, $pager->limit) as $i)
	{
		echo '<li>'.$i.'</li>';
	}
	echo "</ul>";
	
	echo '<p>Page '.$pager->page.' of '.$pager->pageCount.'</p>';
	
	$pager->decorator = 'all';
	
	echo '<p>';
	foreach($pager as $page)
	{
		switch($page['item'])
		{
			case 'current': echo ' <strong>['.$page['number'].']</strong> '; break;
			case 'number': echo ' <a href="?page='.$page['number'].'&amp;offset='.$page['offset'].'">'.$page['number'].'</a> '; break;
		}	
	}
	echo '</p>';
	
	$pager->decorator = 'slider';
	
	echo '<p>';
	foreach($pager as $page)
	{
		switch($page['item'])
		{
			case 'current': echo ' <strong>['.$page['number'].']</strong> '; break;
			case 'number': echo ' <a href="?page='.$page['number'].'">'.$page['number'].'</a> '; break;
			case 'gap': echo " ... "; break;
		}	
	}
	echo '</p>';   
	
	$pager->decorator = 'slider';
	$pager->decorator->chunk = 0;
	$pager->decorator->around = 10;
	
	echo '<p>';
	foreach($pager as $page)
	{
		switch($page['item'])
		{
			case 'current': echo ' <strong>['.$page['number'].']</strong> '; break;
			case 'number': echo ' <a href="?page='.$page['number'].'">'.$page['number'].'</a> '; break;
		}	
	}
	echo '</p>';   
	
	$pager->decorator = 'dotting_slider';
	
	echo '<p>';
	foreach($pager as $page)
	{
		switch($page['item'])
		{
			case 'current': echo ' <strong>['.$page['number'].']</strong> '; break;
			case 'number': echo ' <a href="?page='.$page['number'].'">'.$page['number'].'</a> '; break;
			case 'dot': echo '<a href="?page='.$page['number'].'">.</a>'; break;
		}	
	}
	echo '</p>';
		
	$pager->decorator = 'stepping_slider';
	//$pager->decorator->chunk = 1;
	//$pager->decorator->around = 1;
	
	echo '<p>';
	foreach($pager as $page)
	{
		switch($page['item'])
		{
			case 'current': echo ' <strong>['.$page['number'].']</strong> '; break;
			case 'number': echo ' <a href="?page='.$page['number'].'">'.$page['number'].'</a> '; break;
			case 'step': echo ' <a href="?page='.$page['number'].'"><small>'.$page['number'].'</small></a> '; break;
		}	
	}
	echo '</p>';

	$pager->decorator = 'jumper';
	//$pager->decorator->stepping = 5;
	//$pager->decorator->steps = false;
	
	echo '<p>';
	foreach($pager as $page)
	{
		switch($page['item'])
		{
			case 'current': echo ' <strong>['.$page['number'].']</strong> '; break;
			case 'number': echo ' <a href="?page='.$page['number'].'">'.$page['number'].'</a> '; break;
			case 'step': echo ' <a href="?page='.$page['number'].'"><small>'.$page['number'].'</small></a> '; break;
		}	
	}
	echo '</p>';
	

	$pager->decorator = 'range_slider';
	$pager->decorator->range = 10;
	
	echo '<p>';
	foreach($pager as $page)
	{
		switch($page['item'])
		{
			case 'current': echo ' <strong>['.$page['number'].']</strong> '; break;
			case 'number': echo ' <a href="?page='.$page['number'].'">'.$page['number'].'</a> '; break;
		}	
	}
	echo '</p>';
	
	$pager->decorator = 'range_slider';
	$pager->decorator->range = 9;
	
	echo '<p>';
	foreach($pager as $page)
	{
		switch($page['item'])
		{
			case 'current': echo ' <strong>['.$page['number'].']</strong> '; break;
			case 'number': echo ' <a href="?page='.$page['number'].'">'.$page['number'].'</a> '; break;
		}	
	}
	echo '</p>';

	echo '<p>';
	
	$first = $pager->first;
	$last = $pager->last;
	$previous = $pager->previous;
	$next = $pager->next;

	if($first)
	{
		echo ' <a href="?page='.$first['number'].'">&lArr; first</a> ';
	}
	else
	{
		echo ' <a href="#" style="visibility:hidden">&lArr; first</a> ';
	}

	if($previous)
	{
		echo ' <a href="?page='.$previous['number'].'">&larr; previous</a> ';
	}
	else
	{
		echo ' <a href="#" style="visibility:hidden">&larr; previous</a> ';
	}

	if($next)
	{
		echo ' <a href="?page='.$next['number'].'">next &rarr;</a> ';
	}
	else
	{
		echo ' <a href="#" style="visibility:hidden">next &rarr;</a> ';
	}

	if($last)
	{
		echo ' <a href="?page='.$last['number'].'">last &rArr;</a> ';
	}
	else
	{
		echo ' <a href="#" style="visibility:hidden">last &rArr;</a> ';
	}

	echo '</p>';	

	
}
catch(Opc_Exception $exception)
{
	$handler = new Opc_ErrorHandler;
	$handler->display($exception);
} 
