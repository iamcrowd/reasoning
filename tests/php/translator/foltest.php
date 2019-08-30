<?php
/*

Copyright 2017

Grupo de Investigación en Lenguajes e Inteligencia Artificial (GILIA) -
Facultad de Informática
Universidad Nacional del Comahue

foltest.php

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once("common.php");

// use function \load;
load("umlfol.php", "wicom/translator/fol/");
load("verbalisationUML.php", "wicom/translator/verbalisation/");


use Wicom\Translator\Fol\UMLFol;
use Wicom\Translator\Verbalisation\Verbalisation;


class FolTest extends PHPUnit\Framework\TestCase
{

	## --
	# Test if we can generate the metamodel equivalent to the given UML diagram with classes and subsumptions

        	public function testUMLClassToFol() {
        $json = <<< EOT
{
    "classes": [{
            "name": "Classssssss",
            "attrs": [],
            "methods": [],
            "position": {
                "x": 20,
                "y": 20
            }
        }, {
            "name": "Persona",
            "attrs": [],
            "methods": [],
            "position": {
                "x": 20,
                "y": 20
            }
        }],
    "links": []
}
EOT;
        $expected = <<< EOT
{
    "Classes": [{
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Classssssss",
                    "varp": ["x"]
                }
            }
        }, {
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Persona",
                    "varp": ["x"]
                }
            }
        }],
    "Attribute": [],
    "Links": [],
    "IsA": []
}
EOT;

        $strategy = new UMLFol();
        $strategy->create_fol($json);
//		print_r($strategy->fol);
        $this->assertJsonStringEqualsJsonString($expected, $strategy->get_json(), true);
    }

    public function testUMLClassWithAttributtesToFol() {
        $json = <<< EOT
{
    "classes": [{
            "name": "Classssssss",
            "attrs": [],
            "methods": [],
            "position": {
                "x": 20,
                "y": 20
            }
        }, {
            "name": "Persona",
            "attrs": [{
                    "name": "dni",
                    "datatype": "String"
                }, {
                    "name": "nombre",
                    "datatype": "String"
                }, {
                    "name": "apellido",
                    "datatype": "String"
                }],
            "methods": [],
            "position": {
                "x": 20,
                "y": 20
            }
        },
        {
            "name": "Student",
            "attrs": [{
                    "name": "id",
                    "datatype": "String"
                }, {
                    "name": "enrolldate",
                    "datatype": "Date"
                }],
            "methods": [],
            "position": {
                "x": 808,
                "y": 108
            }
        }],
    "links": []
}
EOT;

        $expected = <<< EOT

{
    "Classes": [{
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Classssssss",
                    "varp": ["x"]
                }
            }
        }, {
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Persona",
                    "varp": ["x"]
                }
            }
        }, {
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Student",
                    "varp": ["x"]
                }
            }
        }
            ],
    "Attribute": [{
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Persona",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "dni",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Persona",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "nombre",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Persona",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "apellido",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Student",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "id",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        },{
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Student",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "enrolldate",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "Date",
                        "varp": ["y"]
                    }
                }
            }
        }
            ],
    "Links": [],
    "IsA": []
}
EOT;

        $strategy = new UMLFol();
        $strategy->create_fol($json);
        //$this->mostrarResultados($strategy->fol);
//		print_r($strategy->fol);
        $this->assertJsonStringEqualsJsonString($expected, $strategy->get_json(), true);

        //$this->verbalisation($strategy->get_json());
    }



    public function testUMLClassAndSubsumptions(){
		$json = <<< EOT
{
"classes": [{"name":"Phone", "attrs":[{
                    "name": "id_Phone",
                    "datatype": "Integer"
                }], "methods":[]},
		    {"name":"CellPhone", "attrs":[{
                    "name": "dni",
                    "datatype": "String"
                }, {
                    "name": "nombre",
                    "datatype": "String"
                }, {
                    "name": "apellido",
                    "datatype": "String"
                }], "methods":[]},
			{"name":"FixedPhone", "attrs":[{
                    "name": "id",
                    "datatype": "String"
                }, {
                    "name": "enrolldate",
                    "datatype": "Date"
                }], "methods":[]}],
"links":   [
			{"classes" : ["CellPhone", "FixedPhone"],
			 "multiplicity" : null,
			 "name" : "r1",
			 "type" : "generalization",
			 "parent" : "Phone",
			 "constraint" : []
			}
		   ]
}
EOT;

$expected = <<< EOT
  {
  	"Classes": [{
  		"forall": {
  			"var": "x",
  			"pred": {
  				"name": "Phone",
  				"varp": ["x"]
  			}
  		}
  	}, {
  		"forall": {
  			"var": "x",
  			"pred": {
  				"name": "CellPhone",
  				"varp": ["x"]
  			}
  		}
  	}, {
  		"forall": {
  			"var": "x",
  			"pred": {
  				"name": "FixedPhone",
  				"varp": ["x"]
  			}
  		}
  	}],
  	"Attribute": [{
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Phone",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "id_Phone",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "Integer",
                        "varp": ["y"]
                    }
                }
            }
        },{
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "CellPhone",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "dni",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        },{
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "CellPhone",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "nombre",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        },{
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "CellPhone",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "apellido",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        },{
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "FixedPhone",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "id",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        },{
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "FixedPhone",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "enrolldate",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "Date",
                        "varp": ["y"]
                    }
                }
            }
        }],
  	"Links": [],
  	"IsA": [{
  			"forall": {
  				"var": "x",
  				"imply": {
  					"pred": {
  						"name": "CellPhone",
  						"varp": ["x"]
  					},
  					"predB": {
  						"name": "Phone",
  						"varp": ["x"]
  					}
  				}
  			}}
                        ,

             {
                    "forall": {
  					"var": "x",
  					"imply": {
  						"pred":{
  							"name": "FixedPhone",
  							"varp": ["x"]
  						},
  						"predB": {
  							"name": "Phone",
  							"varp": ["x"]
  						}
                                        }
  				}
  			}

        ]
}
EOT;

		$strategy = new UMLFOL();
		$strategy->create_fol($json);
		//print_r($strategy->fol);$strategy
		$this->assertJsonStringEqualsJsonString($expected, $strategy->get_json(),true);

                //$this->verbalisation($strategy->get_json());


	}


        	public function testUMLBinaryAssoc0N(){
		$json = <<< EOT
{
    "classes": [{
            "name": "Persona",
            "attrs": [],
            "methods": [],
            "position": {
                "x": 582,
                "y": 95
            }
        }, {
            "name": "Carrera",
            "attrs": [],
            "methods": [],
            "position": {
                "x": 582,
                "y": 501
            }
        }],
    "links": [{
            "name": "estudia",
            "classes": ["Persona", "Carrera"],
            "multiplicity": ["1..*", "1..*"],
            "roles": [null, null],
            "type": "association"
        }]
}

EOT;

        $expected = <<< EOT
{
    "Classes": [{
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Persona",
                    "varp": ["x"]
                }
            }
        }, {
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Carrera",
                    "varp": ["x"]
                }
            }
        }],
    "Attribute": [],
    "Links": [[{
                "forall": {
                    "var": ["x", "y"],
                    "imply": {
                        "pred": {
                            "name": "estudia",
                            "varp": ["x", "y"]
                        },
                        "and": {
                            "pred": {
                                "name": "Persona",
                                "varp": ["x"]
                            },
                            "predB": {
                                "name": "Carrera",
                                "varp": ["y"]
                            }
                        }
                    }
                }
            },  {
                "forall": {
                    "var": ["x"],
                    "imply": {
                        "pred": {
                            "name": "Persona",
                            "varp": ["x"]
                        },
                        "multiplicity": {
                            "min": "1",
                            "#": {
                                "var": ["y"],
                                "pred": {
                                    "name": "estudia",
                                    "varp": ["x", "y"]
                                }
                            },
                            "max": "*"
                            }
                    }
                }
            },  {
                "forall": {
                    "var": ["x"],
                    "imply": {
                        "pred": {
                            "name": "Carrera",
                            "varp": ["y"]
                        },
                        "multiplicity": {
                            "min": "1",
                            "#": {
                                "var": ["x"],
                                "pred": {
                                    "name": "estudia",
                                    "varp": ["x", "y"]
                                }
                            },
                            "max": "*"
                        }
                    }
                }
            }
        ]],
    "IsA": []
}
EOT;

		$strategy = new UMLFol();  //VER SI ESTÁ BIEN PUESTA LA MULTIPLICIDAD!!
		$strategy->create_fol($json);
		//print_r($strategy->meta);
		$this->assertJsonStringEqualsJsonString($expected, $strategy->get_json(),true);

                //$this->verbalisation($strategy->get_json());


	}

        public function testUMLClassAndSubsumptionsWithConstraints() {
        $json = <<< EOT
{
    "classes": [{
            "name": "Persona",
            "attrs": [],
            "methods": []
        }, {
            "name": "Cliente",
            "attrs": [],
            "methods": []
        }, {
            "name": "Profesor",
            "attrs": [],
            "methods": []
        }, {
            "name": "Alumno",
            "attrs": [],
            "methods": []
            }
        ],
    "links": [{
            "name": "r2",
            "classes": ["Cliente", "Profesor", "Alumno"],
            "multiplicity": null,
            "roles": [null, null],
            "type": "generalization",
            "parent": "Persona",
            "constraint": ["disjoint","covering"]
        }]
}
EOT;

        $expected = <<< EOT
{
    "Classes": [{
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Persona",
                    "varp": ["x"]
                }
            }
        }, {
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Cliente",
                    "varp": ["x"]
                }
            }
        }, {
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Profesor",
                    "varp": ["x"]
                }
            }
        }, {
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Alumno",
                    "varp": ["x"]
                }
            }
        }],
    "Attribute": [],
    "Links": [],
    "IsA": [{
            "forall": {
                "var": "x",
                "imply": {
                    "pred": {
                        "name": "Cliente",
                        "varp": ["x"]
                    },
                    "predB": {
                        "name": "Persona",
                        "varp": ["x"]
                    }
                }
            }
        }, {
            "forall": {
                "var": "x",
                "imply": {
                    "pred": {
                        "name": "Profesor",
                        "varp": ["x"]
                    },
                    "predB": {
                        "name": "Persona",
                        "varp": ["x"]
                    }
                }
            }
        }, {
            "forall": {
                "var": "x",
                "imply": {
                    "pred": {
                        "name": "Alumno",
                        "varp": ["x"]
                    },
                    "predB": {
                        "name": "Persona",
                        "varp": ["x"]
                    }
                }
            }
        }, {
            "disjoint": [{
                    "forall": {
                        "var": ["x"],
                        "imply": {
                            "pred": {
                                "name": "Cliente",
                                "varp": ["x"]
                            },
                            "neg": {
                                "pred": {
                                    "name": "Profesor",
                                    "varp": "x"
                                }
                            }
                        }
                    }
                }, {
                    "forall": {
                        "var": ["x"],
                        "imply": {
                            "pred": {
                                "name": "Cliente",
                                "varp": ["x"]
                            },
                            "neg": {
                                "pred": {
                                    "name": "Alumno",
                                    "varp": "x"
                                }
                            }
                        }
                    }
                }, {
                    "forall": {
                        "var": ["x"],
                        "imply": {
                            "pred": {
                                "name": "Profesor",
                                "varp": ["x"]
                            },
                            "neg": {
                                "pred": {
                                    "name": "Alumno",
                                    "varp": "x"
                                }
                            }
                        }
                    }
                }],
            "covering": [{
                    "forall": {
                        "var": ["x"],
                        "imply": {
                            "pred": {
                                "name": "Persona"
                            },
                            "or": [{
                                    "pred": {
                                        "name": "Cliente",
                                        "var": ["x"]
                                    }
                                }, {
                                    "pred": {
                                        "name": "Profesor",
                                        "var": ["x"]
                                    }
                                }, {
                                    "pred": {
                                        "name": "Alumno",
                                        "var": ["x"]
                                    }
                                }]
                        }
                    }
                }]
        }]
}

EOT;


        $strategy = new UMLFOL();
        $strategy->create_fol($json);
        //print_r($strategy->fol);$strategy
        $this->assertJsonStringEqualsJsonString($expected, $strategy->get_json(), true);

        //$this->verbalisation($strategy->get_json());
    }


    public function testUMLFull(){
		$json = <<< EOT
{
    "classes": [{
            "name": "Persona",
            "attrs": [{
                    "name": "nombre",
                    "datatype": "String"
                }, {
                    "name": "apellido",
                    "datatype": "String"
                }, {
                    "name": "direccion",
                    "datatype": "String"
                }],
            "methods": [],
            "position": {
                "x": 582,
                "y": 95
            }
        }, {
            "name": "Cliente",
            "attrs": [{
                    "name": "id_Cliente",
                    "datatype": "String"
                }, {
                    "name": "fecha_Alta",
                    "datatype": "date"
                }],
            "methods": [],
            "position": {
                "x": 582,
                "y": 95
            }
        },{
            "name": "Profesor",
            "attrs": [{
                    "name": "id_Profesor",
                    "datatype": "String"
                }, {
                    "name": "titulo",
                    "datatype": "String"
                }],
            "methods": [],
            "position": {
                "x": 582,
                "y": 95
            }
        },{
            "name": "Alumno",
            "attrs": [{
                    "name": "legajo",
                    "datatype": "String"
                }, {
                    "name": "fecha_Ingreso",
                    "datatype": "date"
                }],
            "methods": [],
            "position": {
                "x": 582,
                "y": 501
            }
        }, {
            "name": "Phone",
            "attrs": [{
                    "name": "id_Phone",
                    "datatype": "Integer"
                }],
            "methods": []
        },
        {
            "name": "CellPhone",
            "attrs": [{
                    "name": "id_CellPhone",
                    "datatype": "String"
                }, {
                    "name": "nombre",
                    "datatype": "String"
                }],
            "methods": []
        },
        {
            "name": "FixedPhone",
            "attrs": [{
                    "name": "id_FixedPhone",
                    "datatype": "String"
                }, {
                    "name": "date",
                    "datatype": "Date"
                }],
            "methods": []
        },
        {
            "name": "Phone_Call",
            "attrs": [{
                    "name": "id_PhoneCall",
                    "datatype": "String"
                }, {
                    "name": "type",
                    "datatype": "String"
                }],
            "methods": []
        }],
    "links": [{
            "name": "estudia",
            "classes": ["Persona", "Carrera"],
            "multiplicity": ["1..*", "1..*"],
            "roles": [null, null],
            "type": "association"
        },{
            "name": "call",
            "classes": ["Phone", "Phone_Call"],
            "multiplicity": ["0..*", "1..1"],
            "roles": [null, null],
            "type": "association"
        },
        {"classes" : ["CellPhone", "FixedPhone"],
			 "multiplicity" : null,
			 "name" : "r1",
			 "type" : "generalization",
			 "parent" : "Phone",
			 "constraint" : ["disjoint","covering"]
			},
        {"classes" : ["Cliente", "Profesor", "Alumno"],
			 "multiplicity" : null,
			 "name" : "r1",
			 "type" : "generalization",
			 "parent" : "Persona",
			 "constraint" : ["covering"]
			}
    ]
}
EOT;

        $expected = <<< EOT
{
    "Classes": [{
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Persona",
                    "varp": ["x"]
                }
            }
        }, {
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Cliente",
                    "varp": ["x"]
                }
            }
        }, {
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Profesor",
                    "varp": ["x"]
                }
            }
        }, {
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Alumno",
                    "varp": ["x"]
                }
            }
        }, {
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Phone",
                    "varp": ["x"]
                }
            }
        }, {
            "forall": {
                "var": "x",
                "pred": {
                    "name": "CellPhone",
                    "varp": ["x"]
                }
            }
        }, {
            "forall": {
                "var": "x",
                "pred": {
                    "name": "FixedPhone",
                    "varp": ["x"]
                }
            }
        }, {
            "forall": {
                "var": "x",
                "pred": {
                    "name": "Phone_Call",
                    "varp": ["x"]
                }
            }
        }],
    "Attribute": [{
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Persona",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "nombre",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Persona",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "apellido",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Persona",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "direccion",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Cliente",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "id_Cliente",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Cliente",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "fecha_Alta",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "date",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Profesor",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "id_Profesor",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Profesor",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "titulo",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Alumno",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "legajo",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Alumno",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "fecha_Ingreso",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "date",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Phone",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "id_Phone",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "Integer",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "CellPhone",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "id_CellPhone",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "CellPhone",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "nombre",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "FixedPhone",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "id_FixedPhone",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "FixedPhone",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "date",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "Date",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Phone_Call",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "id_PhoneCall",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        }, {
            "forall": {
                "var": ["x", "y"],
                "imply": {
                    "and": {
                        "pred": {
                            "name": "Phone_Call",
                            "varp": ["x"]
                        },
                        "predA": {
                            "name": "type",
                            "varp": ["x", "y"]
                        }
                    },
                    "predT": {
                        "name": "String",
                        "varp": ["y"]
                    }
                }
            }
        }],
    "Links": [[{
                "forall": {
                    "var": ["x", "y"],
                    "imply": {
                        "pred": {
                            "name": "estudia",
                            "varp": ["x", "y"]
                        },
                        "and": {
                            "pred": {
                                "name": "Persona",
                                "varp": ["x"]
                            },
                            "predB": {
                                "name": "Carrera",
                                "varp": ["y"]
                            }
                        }
                    }
                }
            }, {
                "forall": {
                    "var": ["x"],
                    "imply": {
                        "pred": {
                            "name": "Persona",
                            "varp": ["x"]
                        },
                        "multiplicity": {
                            "min": "1",
                            "#": {
                                "var": ["y"],
                                "pred": {
                                    "name": "estudia",
                                    "varp": ["x", "y"]
                                }
                            },
                            "max": "*"
                        }
                    }
                }
            }, {
                "forall": {
                    "var": ["x"],
                    "imply": {
                        "pred": {
                            "name": "Carrera",
                            "varp": ["y"]
                        },
                        "multiplicity": {
                            "min": "1",
                            "#": {
                                "var": ["x"],
                                "pred": {
                                    "name": "estudia",
                                    "varp": ["x", "y"]
                                }
                            },
                            "max": "*"
                        }
                    }
                }
            }], [{
                "forall": {
                    "var": ["x", "y"],
                    "imply": {
                        "pred": {
                            "name": "call",
                            "varp": ["x", "y"]
                        },
                        "and": {
                            "pred": {
                                "name": "Phone",
                                "varp": ["x"]
                            },
                            "predB": {
                                "name": "Phone_Call",
                                "varp": ["y"]
                            }
                        }
                    }
                }
            }, {
                "forall": {
                    "var": ["x"],
                    "imply": {
                        "pred": {
                            "name": "Phone",
                            "varp": ["x"]
                        },
                        "multiplicity": {
                            "min": "1",
                            "#": {
                                "var": ["y"],
                                "pred": {
                                    "name": "call",
                                    "varp": ["x", "y"]
                                }
                            },
                            "max": "1"
                        }
                    }
                }
            }, {
                "forall": {
                    "var": ["x"],
                    "imply": {
                        "pred": {
                            "name": "Phone_Call",
                            "varp": ["y"]
                        },
                        "multiplicity": {
                            "min": "0",
                            "#": {
                                "var": ["x"],
                                "pred": {
                                    "name": "call",
                                    "varp": ["x", "y"]
                                }
                            },
                            "max": "*"
                        }
                    }
                }
            }]],
    "IsA": [{
            "forall": {
                "var": "x",
                "imply": {
                    "pred": {
                        "name": "CellPhone",
                        "varp": ["x"]
                    },
                    "predB": {
                        "name": "Phone",
                        "varp": ["x"]
                    }
                }
            }
        }, {
            "forall": {
                "var": "x",
                "imply": {
                    "pred": {
                        "name": "FixedPhone",
                        "varp": ["x"]
                    },
                    "predB": {
                        "name": "Phone",
                        "varp": ["x"]
                    }
                }
            }
        }, {
            "disjoint": [{
                    "forall": {
                        "var": ["x"],
                        "imply": {
                            "pred": {
                                "name": "CellPhone",
                                "varp": ["x"]
                            },
                            "neg": {
                                "pred": {
                                    "name": "FixedPhone",
                                    "varp": "x"
                                }
                            }
                        }
                    }
                }],
            "covering": [{
                    "forall": {
                        "var": ["x"],
                        "imply": {
                            "pred": {
                                "name": "Phone"
                            },
                            "or": [{
                                    "pred": {
                                        "name": "CellPhone",
                                        "var": ["x"]
                                    }
                                }, {
                                    "pred": {
                                        "name": "FixedPhone",
                                        "var": ["x"]
                                    }
                                }]
                        }
                    }
                }]
        }, {
            "forall": {
                "var": "x",
                "imply": {
                    "pred": {
                        "name": "Cliente",
                        "varp": ["x"]
                    },
                    "predB": {
                        "name": "Persona",
                        "varp": ["x"]
                    }
                }
            }
        }, {
            "forall": {
                "var": "x",
                "imply": {
                    "pred": {
                        "name": "Profesor",
                        "varp": ["x"]
                    },
                    "predB": {
                        "name": "Persona",
                        "varp": ["x"]
                    }
                }
            }
        }, {
            "forall": {
                "var": "x",
                "imply": {
                    "pred": {
                        "name": "Alumno",
                        "varp": ["x"]
                    },
                    "predB": {
                        "name": "Persona",
                        "varp": ["x"]
                    }
                }
            }
        }, {
            "covering": [{
                    "forall": {
                        "var": ["x"],
                        "imply": {
                            "pred": {
                                "name": "Persona"
                            },
                            "or": [{
                                    "pred": {
                                        "name": "Cliente",
                                        "var": ["x"]
                                    }
                                }, {
                                    "pred": {
                                        "name": "Profesor",
                                        "var": ["x"]
                                    }
                                }, {
                                    "pred": {
                                        "name": "Alumno",
                                        "var": ["x"]
                                    }
                                }]
                        }
                    }
                }]
        }]
}


EOT;

		$strategy = new UMLFol();
		$strategy->create_fol($json);
		//print_r($strategy->meta);
		$this->assertJsonStringEqualsJsonString($expected, $strategy->get_json(),true);

                $this->verbalisation($strategy->get_json());


	}


    public function mostrarResultados($json){
        foreach ($json as $key => $value) {
            print($key+':');
            print_r($value);
        }
    }


    public function verbalisation($fol) {
        $verbalisation= new Verbalisation();
        $verbalisation->generate_verbalisation($fol);
        print_r($verbalisation->verbalisation);

    }

}
