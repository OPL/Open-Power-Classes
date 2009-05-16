
<html xmlns="http://www.w3.org/1999/xhtml" lang="en_US" xml:lang="en_US">
<head>
	<title>Paginator Test 1</title>
</head>
<body>
<p>
<?php $this->_data['pager']->decorator='all';  ?>
<?php $_sectpager_vals = $this->_data['pager'];  if(is_object($_sectpager_vals) && ($_sectpager_vals instanceof Traversable) && ($_sectpager_vals instanceof Countable) && ($_sectpager_vals->count() > 0)){  foreach($_sectpager_vals as $_sectpager_i => $_sectpager_v){  switch($_sect_v['item']){    case 'number':   ?> <a href="<?php  echo htmlspecialchars('?page='.$_sect_v['number']);   ?>"><?php echo htmlspecialchars($_sect_v['number']);   ?></a> <?php  break;     case 'current':   ?> <strong>[<?php echo htmlspecialchars($_sect_v['number']);   ?>]</strong> <?php  break;     }   }   }   ?>
</p>
</body>
</html>
