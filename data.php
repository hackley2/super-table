<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 8/10/2015
 * Time: 7:51 PM
 */
Class Table
{
    static public function generate()
    {
        // Years
        $now = new DateTime();
        $years[] = $now->format('Y');

        for ($i = 0;
        $i < 5;
        $i++)
        {
        $years[] = $years[$i] - 1;
        }

        // Names
        $firstNames = [
            "Pete", "Declan", "Gill", "Mark", "Kevin", "Andy", "Elaine", "Armin", "Cengiz", "Morgan", "Tariq", "Gary", "Christos", "Oonagh", "Ben", "David", "Tony", "Serge", "Simon", "Tim", "Paul", "Alasdair", "Dawn", "Dominic", "Jennie", "Caroline", "Jon", "Karen", "Jeremy", "James", "Craig", "Mark", "Andy", "Jen", "Peter", "Anand", "Dean", "Marios", "Tonia", "Sharon", "Sharon", "Jonathan", "Andrej", "Peter", "Keat Cheng", "Tom", "Rogier", "Paula", "Constantinos", "Freddy", "Chris", "Clive", "Maria", "Gene", "Lorraine", "Dawn", "Fiona", "Joanne", "Helen", "Alexandra", "Irene", "James", "Stuart", "Susan", "Hugh", "Andrew", "Sally", "Petros", "Matt", "Julie", "Steven", "Simon", "Mark", "Adam", "Ken", "Laure", "Sean", "Barbara", "Philippe", "Mary", "Ken", "Derek", "Thomas", "Andrea", "Danaa", "Martin", "Michael", "Nigel", "Mike", "Janice", "Stuart", "Paul", "Yann", "Claire", "Maria", "Karen", "Rob", "Helen", "Ruth", "Jacki", "Anna", "Sharon", "Stephen", "Anna", "Leonor", "Matthew", "Michel", "Lee", "Emily", "Carlos", "Sharon", "Lisa", "Helen", "Therese", "Robin", "Tina", "Maria", "Elizabeth", "Lisa", "Julia", "Chris", "Nicky", "Eve", "Karen", "Tim", "Jo", "Marc", "Nicky", "Heather", "Kathy", "Kieran", "Anita", "Derek", "Anna", "Steph", "Lizzie", "Gary", "Mike", "Spyros", "Gerda", "Casey", "Dawn", "Mark", "Antony"
        ];
        $lastNames = [
            "Brunson", "Lamantia", "Damico", "Sheridan", "Lapp", "Sinclair", "Neves", "Marie", "Argento", "Wiliams", "Caprio", "Shahan", "Rue", "Pauling", "Gummer", "Westerlund", "Doolittle", "Istre", "Palen", "Coppinger", "Keister", "Naugle", "Ritchey", "Yerger", "Bethea", "Ishmael", "Berrier", "Ketelsen", "Dawdy", "Rolando", "Kidwell", "Garrison", "Dipasquale", "Lococo", "Lanphear", "Beaman", "Vaillancourt", "Dozier", "Maynard", "Koonce", "Sisemore", "Brody", "Haugen", "Hyslop", "Sturrock", "Mable", "Jeansonne", "Ollie", "Ousley", "Keech", "Whitworth", "Lazos", "Trombley", "Wiltse", "Halstead", "Kavanaugh", "Hartlage", "Feld", "Martineau", "Salaam", "Pauline", "Edmisten", "Pratte", "Sidler", "Spoto", "Permenter", "Skipworth", "Balogh", "Imburgia", "Lemay", "Boruff", "Chason", "Cardillo", "Kahle", "Limones", "Butts", "Hollier", "Stanley", "Georges", "Hartley", "Moorhead", "Langevin", "Schnieders", "Worley", "Hennis", "Bitting", "Stead", "Schulenberg", "Dowdell", "Erhart", "Marshell", "Diller", "Rickert", "Honea", "Geers", "Donlon", "Nunnally", "Malm", "Aubert", "Shifflett"
        ];

        shuffle($firstNames);
        shuffle($lastNames);

        $columns = [
            'Last Name',
            'First Name',
        ];

        //sub headers that the year headers will have
        $subColumns = [
            'Average Sales',
            'Unique Clients',
            'Sales Transactions'
        ];
        $tableCell = [
            'data' => '',
            'attr' => [],
        ];


        //initialize the $table variable and the table's structure.
        //each section(thead, tbody, tfooter) has an array per row and an array per cell per row
        //each cell and row contain 'data' and 'attr' keys that contain that html's contents and
        // attributes. there will be one key/value pair per attribute
        $table = [
            'thead' =>
                [
                    [                                   // row 0
                        'data' => [
                            [                               // cell
                                'data' => '',
                                'attr' => [
                                    'colspan' => '2'
                                ],
                            ]
                        ],
                        'attr' => [],
                    ],
                ],
            'tbody' => [

            ],
        ];

        //setup the first two columns of thead's row 1 since they don't need years

        $table['thead'][1] = $tableCell;
        //cell one in row 1
        $table['thead'][1]['data'][0] = $tableCell;
        $table['thead'][1]['data'][0]['data'] = $columns[0];
        //cell two in row 1
        $table['thead'][1]['data'][1] = $tableCell;
        $table['thead'][1]['data'][1]['data'] = $columns[1];

        //finish adding years to the first row in the table's thead
        foreach ($years as $year) {
            $cell = $tableCell;
            $cell['data'] = $year;
            $cell['attr']['colspan'] = count($subColumns);
            $table['thead'][0]['data'][] = $cell;

            //add the year's sub headers
            foreach ($subColumns as $subColumn) {
                $cell = $tableCell;
                $cell['data'] = $subColumn;
                $table['thead'][1]['data'][] = $cell;
            }
        }


        // Avg. Sales per name
        $sales = [];
        // Number of Unique Clients
        $numberOfUniqueClients = [];
        // Number of sales transactions
        $numberOfSales = [];
        // Number of names we have
        $numberOfNames = min(count($firstNames), count($lastNames));

        for ($j = 0; $j < count($years); $j++) {
            for ($i = 0; $i < $numberOfNames; $i++) {
                $sales[] = mt_rand(4000, 100000);
                $numberOfUniqueClients[] = $nuc =  mt_rand(31, 103);
                $numberOfSales[] = mt_rand($nuc, 133);
            }
        }

        //construct the body of the table row by row where $i keeps track of the row
        //$j keeps track of which element of the sales figures we're on.
        $j = 0;
        for ($i = 0; $i < $numberOfNames; $i++) {
            //setup the row
            $table['tbody'][$i] = $tableCell;

            //column one - last name
            $cell = $tableCell;
            $cell['data'] = $lastNames[$i];
            $table['tbody'][$i]['data'][] = $cell;

            //column one - first name
            $cell = $tableCell;
            $cell['data'] = $firstNames[$i];
            $table['tbody'][$i]['data'][] = $cell;

            foreach ($years as $year) {

                //sales average per year
                $cell = $tableCell;
                $cell['data'] = $sales[$j];
                $table['tbody'][$i]['data'][] = $cell;

                //unique clients per year
                $cell = $tableCell;
                $cell['data'] = $numberOfUniqueClients[$j];
                $table['tbody'][$i]['data'][] = $cell;

                //number of sales per year
                $cell = $tableCell;
                $cell['data'] = $numberOfSales[$j++];
                $table['tbody'][$i]['data'][] = $cell;
            }

        }

        return $table;
    }
}