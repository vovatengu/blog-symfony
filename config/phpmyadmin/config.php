<?php

// Docker: connect to db container via TCP, disable controluser (not configured in Docker MySQL)
$cfg['Servers'][1]['host'] = 'db';
$cfg['Servers'][1]['connect_type'] = 'tcp';
$cfg['Servers'][1]['controlhost'] = '';
$cfg['Servers'][1]['controluser'] = '';
$cfg['Servers'][1]['controlpass'] = '';
