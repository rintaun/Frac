<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

// commands to run after Fwork::__destruct is executed. Full access to the Fwork object is provided in its state at the end of Fwork::__destruct.

$session = &SesMan::getInstance();
unset($session);
