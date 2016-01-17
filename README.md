# SuperTable
## Making HTML tables Super since January 2016
---
This jQuery plugin makes the top header and left row of an HTML table intuitively scroll
with the browser window. You can also setup your table to have collapsible rows and
columns.
   
---

#Scrolling

Setting up your table header (the `<thead> ... </thead>` section) and the left-most column
of your table to scroll with the the browser is easy.

```
$(yourTable).superTable();
``` 


Want the table header to scroll but not the left-most column?

```
$(yourTable).superTable({
    scrollColumn : false
});
``` 


---

#Collapsing Columns and Headers

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

Check out the example/index.html file to see an example of collapsible rows and 
columns in action. Click on the `<th>` cells or a row's fist `<td>` cell to collapse 
a group of columns or rows respectively.
 

---

#Settings

##Configure Settings

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


##List of all possible optional settings 

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



Table <tr> rows that have the "rowHideable" class are collapsible. Table <td> cells
elements that have the "columnsHideable" class are also collapsible. 




