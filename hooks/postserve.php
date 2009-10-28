<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

// commands to run after Fwork::serve is executed. Full access to the Fwork object is provided in its state at the end of Fwork::serve.

$session = SesMan::getInstance();
$session['lastpage'] = $path;
