# SuperTable
#### Making HTML tables Super since January 2016

This jQuery plugin makes the header and left row of an HTML table intuitively scroll
with the browser window. You can also setup your table to have collapsible rows and
columns.

##Scrolling

Setting up your `<thead> ... </thead>` table header and the left-most column
of your table to scroll with the the browser is easy!

```
$(yourTable).superTable();
``` 

Want the table header to scroll but not the left-most column?

```
$(yourTable).superTable({
    scrollColumn : false
});
``` 

##Collapsing Columns and Headers

Large HTML tables can be very cumbersome in a web browser. That's why most reports get 
exported directly to CSV. But that doesn't provide a good user experience if the user 
needs to check an updated report frequently. This is where collapsible rows and 
columns come into play. They allow a complex table to be quickly reduced to only the 
most important information while still preserving all the fine-grained details.

When you configure your table to support collapsing headers, clicking a `<th>` element
will collapse your table's columns. Similarly, when your table is configured to 
support collapsing rows, clicking a `<td>` element that's the first `<td>` element of a
row will collapse your table's rows. If you need to group rows and columns into
groups that collapse and expand together, you can do that too! 

###Notes on how to setup collapsible rows

* `<tr>` rows that have the `rowHideable` class are collapsible. 
* `<tr>` rows that share the same value for their optional `data-ST-group` 
  attribute are considered to be a group of rows that will collapse and expand together.
* `<tr>` rows that have the `data-ST-group` but not the `rowHideable` class
  will not themselves collapse, but will collapse other `rowHideable` rows in the 
  group when the row's first `td`/`tr` cell is clicked on
* `<tr>` rows that have neither the `data-ST-group` nor the `rowHideable` class
   will not themselves collapse, but will collapse all other `rowHideable` rows
   when the row's first `td`/`tr` cell is clicked on

###Notes on how to setup collapsible columns

* `<th>` and `<td>` cells that have the `columnHideable` class are collapsible.
* Use the `col-span-min` and `col-span-max` attributes when using collapsible columns 
  with a table that has cells that use the `col-span` attribute.
* `col-span-min` defines how many columns a cell should span when all columns, or the
  group of columns that the cell belongs to, are collapsed
* `col-span-max` defines how many columns a cell should span when all columns, or the
  group of columns that the cell belongs to, are expanded
* Table `<th>` and `<td>` cells that share the same value for their
  optional `data-ST-group` attribute are considered to be a group of rows that will 
  collapse and expand together.
* Table `<th>` cells that aren't part of a group, will expand/collapse
  all `columnHideable` cells when clicked

###Expanding/Collapsing the entire table at once

It is sometimes handy to expand or collapse the entire table at once. If both row 
and column collapsing are enabled, a `th` cell that is also the first cell of a row
will trigger both row and columns to expand/collapse at once.

You can designate another HTML element on the page to trigger a table's 
expand/collapse all event by setting that element's optional `data-super-table` 
attribute to the id attribute of the table in question.


#Try it out yourself

Check out the example/index.html file to see a nearly comprehensive example of table 
scrolling and collapsible rows and columns in action. Click on the `<th>` cells or 
a row's first `<td>`/`<th>` cell to collapse a group of columns or rows respectively.


##Settings

###How to configure settings

Default settings can be set independently of initializing a SuperTable.
```
    $.fn.superTable.defaults.columnCollapse = true;
    $.fn.superTable.defaults.columnCollapsedClass = 'collapsedColumn';
    
```

Otherwise, settings can be configured when initializing a SuperTable by passing your 
preferred options via an object passed as the first parameter of the superTable() function

```
    $('#demoTable1').superTable({
        rowCollapse = true,
        rowCollapsedClass : 'collapsedColumn',
        rowExpandedClass : 'expandedColumn'
    });
```


###List of all possible optional settings 

- __scrollHead__ - Bool (default = true) When false, table header scrolling 
functionality is disabled

- __scrollColumn__ - Bool (default = true) When false, table column scrolling 
functionality is disabled

- __rowCollapse__ - Bool (default = false) When true, collapsing row functionality
is enabled

- __rowCollapsedClass__ - String (default = '') If provided, this class will get added
to all collapsed <tr> elements

- __rowExpandedClass__ - String (default = '') If provided, this class will get added
to all Expanded <tr> elements

- __columnCollapsedClass__ - String (default = '') If provided, this class will get added
to all collapsed <th> and <td> elements

- __columnExpandedClass__ - String (default = '') If provided, this class will get added
to all Expanded <th> and <td> elements

- __startCollapsed__ - Bool (default = false) If true, the table will start collapsed
 
- __remove__ - Bool (default = false)  If true, all superTable attributes will be 
removed from the specified HTML table(s)






