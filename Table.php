<?php

Class Table
{

    /**
     * initialize the $table variable and the table's structure.
     * - each section(thead, tbody, tfooter) has an array per row
     *   and an array per cell per row
     * - each cell and row contain 'data' and 'attr' keys that
     *   contain that html's contents and attributes. there will
     *   be one key/value pair per attribute
     */
    protected $table = [
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


    protected $tableCell = [
        'data' => '',
        'attr' => [],
    ];

    protected $years = [];

    protected $nameColumns = [
        'Last Name',
        'First Name',
    ];

    //sub headers that the year headers will have
    protected $subColumns = [
        'Average Sales',
        'Unique Clients',
        'Sales Transactions'
    ];

    // B2B & B2C
    protected $departments = [
        'B2C Sales Agents',
        'B2B Sales Agents'
    ];

    // Names
    protected $firstNames = [
        "Pete", "Declan", "Gill", "Mark", "Kevin", "Andy", "Elaine", "Armin",
        "Cengiz", "Morgan", "Tariq", "Gary", "Christos", "Oonagh", "Ben",
        "David", "Tony", "Serge", "Simon", "Tim", "Paul", "Alasdair", "Dawn",
        "Dominic", "Jennie", "Caroline", "Jon", "Karen", "Jeremy", "James",
        "Craig", "Mark", "Andy", "Jen", "Peter", "Anand", "Dean", "Marios",
        "Tonia", "Sharon", "Sharon", "Jonathan", "Andrej", "Peter",
        "Keat Cheng", "Tom", "Rogier", "Paula", "Constantinos", "Freddy",
        "Chris", "Clive", "Maria", "Gene", "Lorraine", "Dawn", "Fiona",
        "Joanne", "Helen", "Alexandra", "Irene", "James", "Stuart", "Susan",
        "Hugh", "Andrew", "Sally", "Petros", "Matt", "Julie", "Steven",
        "Simon", "Mark", "Adam", "Ken", "Laure", "Sean", "Barbara", "Philippe",
        "Mary", "Ken", "Derek", "Thomas", "Andrea", "Danaa", "Martin",
        "Michael", "Nigel", "Mike", "Janice", "Stuart", "Paul", "Yann",
        "Claire", "Maria", "Karen", "Rob", "Helen", "Ruth", "Jacki", "Anna",
        "Sharon", "Stephen", "Anna", "Leonor", "Matthew", "Michel", "Lee",
        "Emily", "Carlos", "Sharon", "Lisa", "Helen", "Therese", "Robin",
        "Tina", "Maria", "Elizabeth", "Lisa", "Julia", "Chris", "Nicky",
        "Eve", "Karen", "Tim", "Jo", "Marc", "Nicky", "Heather", "Kathy",
        "Kieran", "Anita", "Derek", "Anna", "Steph", "Lizzie", "Gary", "Mike",
        "Spyros", "Gerda", "Casey", "Dawn", "Mark", "Antony"
    ];
    protected $lastNames = [
        "Brunson", "Lamantia", "Damico", "Sheridan", "Lapp", "Sinclair",
        "Neves", "Marie", "Argento", "Wiliams", "Caprio", "Shahan", "Rue",
        "Pauling", "Gummer", "Westerlund", "Doolittle", "Istre", "Palen",
        "Coppinger", "Keister", "Naugle", "Ritchey", "Yerger", "Bethea",
        "Ishmael", "Berrier", "Ketelsen", "Dawdy", "Rolando", "Kidwell",
        "Garrison", "Dipasquale", "Lococo", "Lanphear", "Beaman",
        "Vaillancourt", "Dozier", "Maynard", "Koonce", "Sisemore", "Brody",
        "Haugen", "Hyslop", "Sturrock", "Mable", "Jeansonne", "Ollie",
        "Ousley", "Keech", "Whitworth", "Lazos", "Trombley", "Wiltse",
        "Halstead", "Kavanaugh", "Hartlage", "Feld", "Martineau", "Salaam",
        "Pauline", "Edmisten", "Pratte", "Sidler", "Spoto", "Permenter",
        "Skipworth", "Balogh", "Imburgia", "Lemay", "Boruff", "Chason",
        "Cardillo", "Kahle", "Limones", "Butts", "Hollier", "Stanley",
        "Georges", "Hartley", "Moorhead", "Langevin", "Schnieders", "Worley",
        "Hennis", "Bitting", "Stead", "Schulenberg", "Dowdell", "Erhart",
        "Marshell", "Diller", "Rickert", "Honea", "Geers", "Donlon",
        "Nunnally", "Malm", "Aubert", "Shifflett"
    ];

    protected  $numberOfColumns = 0;

    protected $collapsibleColumnClass = 'columnHideable';
    protected $collapsibleRowClass = 'rowHideable';


    // Avg. Sales per name
    protected $sales = [];
    protected $salesYearlySum = [];
    // Number of Unique Clients
    protected $numberOfUniqueClients = [];
    protected $numberOfUniqueClientsYearlySum = [];
    // Number of sales transactions
    protected $numberOfSales = [];
    protected $numberOfSalesYearlySum = [];

    // Number of names we have
    protected $numberOfNames = 0;
    // As we populate rows, there are some rows that don't have a name
    // Keep track of that difference
    protected $nameIndexOffset = 0;

    // Keeps track of the current row that's being generated
    protected $currentRow = 0;
    // Keeps track of the current year that cells are being generated for.
    // Helps with summing totals
    protected $currentYear = 0;

    public function generate()
    {
        $this->setYears();

        $this->shuffleNames();

        $this->setNumberOfColumns();

        // setup the first two columns of the table head's first row
        $this->addNameColumnHeadersToTable();

        // Add years to the first row in the table's thead
        $this->addYearColumnHeadersToTable();

        $this->setNumberOfNames();

        $this->generateSalesNumbers();

        // Construct the body of the table row by row where:
        $this->generateTableBody();

        return $this->table;
    }

    /**
     * Initialize the years attribute
     */
    private function setYears(){
        // Years
        $now = new DateTime();
        $this->years[] = $now->format('Y');

        for ($i = 0; $i < 5; $i++) {
            $this->years[] = $this->years[$i] - 1;
        }
    }

    /**
     * Shuffle the names, so it looks different every time it's generated
     */
    private function shuffleNames(){
        shuffle($this->firstNames);
        shuffle($this->lastNames);
    }

    /**
     * Count the total number of columns the table is going to have.
     *
     * Name columns + years * sub-columns
     */
    private function setNumberOfColumns()
    {
        $this->numberOfColumns = count($this->nameColumns) +
                                 count($this->years)*count($this->subColumns);
    }

    /**
     * Add the name column headers to the table
     *
     * The name headers should be the first tow th elements in the thead
     */
    private function addNameColumnHeadersToTable()
    {
        $this->table['thead'][1] = $this->tableCell;
        // cell one in row 1
        $this->table['thead'][1]['data'][0] = $this->tableCell;
        $this->table['thead'][1]['data'][0]['data'] = $this->nameColumns[0];
        // cell two in row 1
        $this->table['thead'][1]['data'][1] = $this->tableCell;
        $this->table['thead'][1]['data'][1]['data'] = $this->nameColumns[1];
    }

    /**
     * Add the year column headers to the table
     *
     * Each year column spans multiple sub-columns. Thus these th elements
     * get the colpasn, data-colspanmin, and data-solspanmax attributes
     */
    private function addYearColumnHeadersToTable()
    {
        foreach ($this->years as $year) {
            // Add the year th elements to the first row
            $cell = $this->tableCell;
            $cell['data'] = $year;
            $cell['attr']['colspan'] = count($this->subColumns);
            $cell['attr']['data-colspanmin'] = 1;
            $cell['attr']['data-colspanmax'] = count($this->subColumns);
            $this->table['thead'][0]['data'][] = $cell;

            // Add the year's sub header th elements to the second row
            foreach ($this->subColumns as $key => $subColumn) {
                $cell = $this->tableCell;
                $cell['data'] = $subColumn;
                // We want the first sub-column to show even when the years
                // are collapsed
                if($key > 0){
                    $cell['attr'] = ['class' => $this->collapsibleColumnClass];
                }
                $this->table['thead'][1]['data'][] = $cell;
            }
        }
    }

    /**
     * Returns the total number of name pairs available
     *
     * @return int
     */
    private function getNumberOfNames()
    {
        return $this->numberOfNames;
    }

    /**
     * Figure out the total number of name pairs available
     */
    private function setNumberOfNames()
    {
        $this->numberOfNames = min(count($this->firstNames),
                                   count($this->lastNames));
    }

    /**
     * Populate the sales numbers via random number generators
     */
    private function generateSalesNumbers()
    {
        foreach($this->years as $year) {
            for ($i = 0; $i < $this->getNumberOfNames(); $i++) {
                $this->sales[] = mt_rand(4000, 100000);
                $this->numberOfUniqueClients[] = $nuc =  mt_rand(31, 103);
                $this->numberOfSales[] = mt_rand($nuc, 133);
            }
            $this->salesYearlySum[$year] = 0;
            $this->numberOfUniqueClientsYearlySum[$year] = 0;
            $this->numberOfSalesYearlySum[$year] = 0;
        }
    }

    /**
     * Generate the body of the table
     */
    private function generateTableBody()
    {
        // $j keeps track of which element of the sales figures we're on.
        $j = 0;
        // $departmentKey
        $departmentKey = 0;

        // Total number of rows is equal to the number of names plus
        // the number of departments times two (each department has a header
        // row and a total row)
        $totalNumberOfRows = $this->numberOfNames + count($this->departments)*2 ;

        // $i keeps track of the row
        for ($i = 0; $i < $totalNumberOfRows; $i++) {
            $this->currentRow = $i;
            $currentDepartment = $this->departments[$departmentKey];

            // Insert department header rows in the first row & in the middle
            // of the table
            if($i == 0 || $i == $this->getMiddleRow()){
                $this->insertHeadRow($i, $currentDepartment);
                $this->nameIndexOffset++;
            }
            // Add a total row at the end of each department section
            elseif($i == $this->getMiddleRow() - 1
                || $i == $totalNumberOfRows - 1
            ) {
                $this->insertTotalRowAndColumns($i, $departmentKey++);
                $this->nameIndexOffset++;
            }
            // Add a normal row consisting of a sales person's stats
            else {
                // setup the row
                $this->insertCollapsibleTableRow(
                    $i,
                    ['data-ST-group' => $currentDepartment]
                );

                $this->insertNameColumns($i);

                foreach ($this->years as $yearKey => $year) {
                    $this->currentYear = $year;

                    $this->insertSubColumnCells($i, $j++);
                }
            }

        }
    }

    private function getMiddleRow()
    {
        return round($this->numberOfNames / 2);
    }

    /**
     * Insert a header row into the table.
     *
     * A header row is a row that describes a group of rows via left-justified
     * title. Header rows don't collapse.
     *
     * @param int $tableRowNumber
     * @param string $currentDepartment
     */
    private function insertHeadRow($tableRowNumber, $currentDepartment)
    {
        // setup the row
        $attributes = [
            'data-ST-group' => $currentDepartment,
            'class' => 'headerRow'
        ];
        $this->insertTableRow($tableRowNumber, $attributes);

        // column one - Department name (colspan's the name columns)
        $attributes = ['colspan' => count($this->nameColumns)];
        $this->insertTableCell($tableRowNumber,$currentDepartment,$attributes);

        // column two - empty space (colspan's the rest of the table)
        $columnsLeftOver = $this->numberOfColumns - count($this->nameColumns);
        $attributes = ['colspan' => $columnsLeftOver];
        $this->insertTableCell($tableRowNumber, '', $attributes);
    }

    /**
     * Insert a row into the table that shows a total of the previous rows
     * This row is essentially the same as a head row
     *
     * @param int $tableRowNumber
     * @param int $currentDepartmentKey
     */
    private function insertTotalRowAndColumns($tableRowNumber, $currentDepartmentKey)
    {
        // setup the row
        $attributes = [
            'data-ST-group' => $this->departments[$currentDepartmentKey],
            'class' => 'totalRow'
        ];
        $this->insertTableRow($tableRowNumber, $attributes);

        // column one - Total
        $this->insertTableCell(
            $tableRowNumber,
            $this->departments[$currentDepartmentKey].' Total',
            ['colspan' => 2]
        );

        foreach($this->years as $year){
            $this->insertSubColumnCellsForTotalRow($tableRowNumber, $currentDepartmentKey);
        }
    }

    /**
     * Inserts a column into the given row number
     *
     * @param int $tableRowNumber Given row number
     * @param string|int $data    Data/text that the cell should have
     * @param array $attributes   Attributes
     */
    private function insertTableCell($tableRowNumber, $data, $attributes = []){
        $cell = $this->tableCell;
        $cell['data'] = $data;
        $cell['attr'] = $attributes;
        $this->table['tbody'][$tableRowNumber]['data'][] = $cell;
    }

    /**
     * Inserts a collapsible row into the table. If $rowGroup is provided
     * then the row will get a 'data-ST-group' attribute, so that it can
     * expand and collapse with the rest of the group
     *
     * @param int $tableRowNumber Row number
     * @param array $attributes   Attributes to apply to the row
     */
    private function insertTableRow($tableRowNumber, $attributes = [])
    {
        $row = $this->tableCell;
        if($attributes){
            $row['attr'] = $attributes;
        }
        $this->table['tbody'][$tableRowNumber] = $row;
    }

    /**
     * Inserts a collapsible row into the table
     *
     * @param int $tableRowNumber
     * @param array $attributes
     */
    private function insertCollapsibleTableRow($tableRowNumber,$attributes=[]){
        $attributes = (['class' => $this->collapsibleRowClass] + $attributes);
        $this->insertTableRow($tableRowNumber, $attributes);
    }

    /**
     * Inserts the two name columns into a row.
     *
     * This method is typically called immediately after creating a row since
     * the name columns are the first two columns of the table
     *
     * @param int $tableRowNumber
     */
    private function insertNameColumns($tableRowNumber)
    {

        // column one - last name
        $cell = $this->tableCell;
        $cell['data'] = $this->lastNames[$tableRowNumber - $this->nameIndexOffset];
        $this->table['tbody'][$tableRowNumber]['data'][] = $cell;

        // column one - first name
        $cell = $this->tableCell;
        $cell['data'] = $this->firstNames[$tableRowNumber - $this->nameIndexOffset];
        $this->table['tbody'][$tableRowNumber]['data'][] = $cell;
    }

    /**
     * Inserts cells for each sub-column
     *
     * @param int $tableRowNumber
     * @param int $salesKey The index of the sales person's data arrays
     */
    private function insertSubColumnCells($tableRowNumber, $salesKey)
    {
        // Sales average per year
        $this->insertTableCell($tableRowNumber, self::formatMonetaryValue($this->sales[$salesKey]));
        // Yearly sum of sales averages
        $this->salesYearlySum[$this->currentYear] += $this->sales[$salesKey];

        // The second and third columns should be collapsible
        $attributes = ['class' => $this->collapsibleColumnClass];

        // Unique clients per year
        $this->insertTableCell(
            $tableRowNumber,
            self::formatNumber($this->numberOfUniqueClients[$salesKey]),
            $attributes
        );
        // Yearly sum of unique clients count
        $this->numberOfUniqueClientsYearlySum[$this->currentYear] +=
            $this->numberOfUniqueClients[$salesKey];

        // Number of sales per year
        $this->insertTableCell(
            $tableRowNumber,
            self::formatNumber($this->numberOfSales[$salesKey]),
            $attributes
        );
        // Yearly sum of sales counts
        $this->numberOfSalesYearlySum[$this->currentYear] +=
            $this->numberOfSales[$salesKey];
    }

    /**
     * Calculate the totals for the department
     *
     * @param int $tableRowNumber
     * @param int $currentDepartmentKey
     */
    private function insertSubColumnCellsForTotalRow($tableRowNumber, $currentDepartmentKey)
    {
        $salesFiguresStartKey = $currentDepartmentKey*$this->getMiddleRow();
        $salesFiguresEndKey = ($salesFiguresStartKey > 0)?
            ($salesFiguresStartKey + $this->getMiddleRow() - 1) : null;

        // Sales average per year
        $salesFigures = array_slice($this->sales,$salesFiguresStartKey,$salesFiguresEndKey);
        $totalAvgSales = array_sum($salesFigures);
        $this->insertTableCell($tableRowNumber, self::formatMonetaryValue($totalAvgSales));

        // The second and third columns should be collapsible
        $attributes = ['class' => $this->collapsibleColumnClass];

        // Unique clients per year
        $clientFigures = array_slice($this->numberOfUniqueClients,$salesFiguresStartKey,$salesFiguresEndKey);
        $totalClients = array_sum($clientFigures);

        $this->insertTableCell(
            $tableRowNumber,
            self::formatNumber($totalClients),
            $attributes
        );

        // Number of sales per year
        $numberOfSalesFigures = array_slice($this->numberOfSales,$salesFiguresStartKey,$salesFiguresEndKey);
        $totalNumberOfSales = array_sum($numberOfSalesFigures);
        $this->insertTableCell(
            $tableRowNumber,
            self::formatNumber($totalNumberOfSales),
            $attributes
        );
    }

    public static function formatNumber($numberToFormat){
        return number_format($numberToFormat,0);
    }

    public static function formatMonetaryValue($numberToFormat){
        return '$'.self::formatNumber($numberToFormat);
    }
}