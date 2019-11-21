![tests](http://crowd.fi.uncoma.edu.ar/crowd2/Trun/results/reasoning.svg)
![overall_tests](http://crowd.fi.uncoma.edu.ar/crowd2/Trun/results/all.svg)

# Reasoner API Back-end

API entry points:

- querying/*.php
- translate/*.php

# Requirements

## Configs
This module requires configuration. See config/* files.

- Set the temporal path.
- Reasoner paths.

## Reasoner Programs

This module requires third party applications. See http://crowd.fi.uncoma.edu.ar/reasoners/ URL to download them.

- Racer : https://github.com/ha-mo-we/Racer/ released under the BSD-3 clause.
- Konclude : http://derivo.de/en/products/konclude/ released under the LGPL 2.1.

The reasoners should be installed at the `run/` directory. Write the path at the config.php file.

Remember to set executable perms to the reasoners: 

    chmod +x run/Racer run/Konclude

Create a blank file at `run/input-file.owllink`.

# Testing 
The tests directory has got all the PHP tests. 

## PHPUnit
Test can be executed using PHPUnit. Download it from https://phpunit.de/ and make it available on your PATH. Remember to check your repository if you are using a GNU/Linux distribution.

# License
![AGPL3 logo](https://www.gnu.org/graphics/agplv3-with-text-100x42.png)

This work is under the Affero General Public License version 3. See the complete text at the COPYING.txt file or at the following URL: https://www.gnu.org/licenses/agpl-3.0.html
