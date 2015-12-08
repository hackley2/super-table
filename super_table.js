/**
 * scrollTableHead() makes the Thead of any Table scroll with the browser window;
 * This is sometimes preferable over the pure CSS scrolling Thead as it gives the user a more fluid
 * experience if they prefer not to have more than the one scroll-bar in the browser window.
 * 
 * tables should have position:relative 
 * 
 */
(function ( $ ) {


$.fn.superTable = function(options){
	
	var origTable = this;
	
	//Start in the scrollTable function
	scrollTable(options);
	
	//Return the original jQuery object
	return this;
	
	function scrollTable(options){
		rowC = false;
		colC = false;
		startC = false;
		colHead = true;
		rowHead = true;
		remove = false;
		rowEclass = '';
		rowCclass = '';
		colEclass = '';
		colCclass = '';
		
		for(var i in options){
			switch(i){
				case "rowCollapse":
					rowC = true;
					break;
				case "colCollapse":
					colC = true;
					break;
				case "startCollapsed":
					startC = true;
					break;
				case "scrollColHeadOnly":
					rowHead = false;
					break;
				case "scrollRowHeadOnly":
					colHead = false;
					break;
				case "rowCollapsedClass":
					rowCclass = options[i];
					break;
				case "rowExpandedClass":
					rowEclass = options[i];
					break;
				case "colCollapsedClass":
					colCclass = options[i];
					break;
				case "colExpandedClass":
					colEclass = options[i];
					break;
				case "remove":
					remove = true;
					break;
			}
		}
		
		//tclass is a class that is unique to the table and clone tables
		var tclass = '';

		if(origTable.attr("id") == null || origTable.attr("id") == ''){
			var i = 1;
			for (var i=0; tclass = ''; i++) {
			  if($('.ST_'+i).length == 0){
			  	tclass = 'ST_'+i;
			  }
			};
		}else{
			tclass = 'ST_'+origTable.attr('id');
		}
		 
		
		origTable.addClass(tclass);
		
		//enable scrollable thead rows and left column
		ST(rowHead,colHead,remove);
		
		//if remove is set, then then the super table elements have already been removed
		if(remove == true){
			//just return
			return origTable;
		}
		//enable collapsible rows
		if(rowC){
			collapsableRows(tclass,rowHead,colHead,rowCclass,rowEclass);
		}
		//enable collapsible columns
		if(colC){
			collapsableColumns(tclass,rowHead,colHead,colCclass,colEclass);
		}
		//start collapsed
		if(startC){
			rowCol(tclass,rowHead,colHead,false,rowCclass,rowEclass);
			colCol(tclass,rowHead,colHead,false,colCclass,colEclass);
		}
	}
	
	/**
	 * Super Table starter function.  initializes the scrollable column and header if needed
	 * 
	 * @param rowHead boolean, if true, then the table will be given a scrollable table head
	 * @param colHead boolean, if true, then the table's left-most column will be scrollable
	 * 
	 */
	function ST(rowHead,colHead,remove){
		if(colHead){
			scrollTableHead(remove);
		}
		if(rowHead){
			scrollTableLColumn(remove);
		}
	}
	
	/**
	 * called once to initialize the scrolling table head functionality on the given table
	 * 
	 * @param remove boolean, if true, super table functionality is removed
	 * 
	 */
	function scrollTableHead(remove){
		//setup the cloned header
		var cloneID = origTable.attr("id")+"STH";
		var cloneSelect = "#"+cloneID;
		var cloneSelectDiv = cloneSelect+"div";

		if(remove){
			//remove scrolling table head and return
			$(cloneSelect).remove();
			return;
		}

		//insert the cloned header into the DOM
		//or update the table header if the header already exists 
		if($(cloneSelect).length != 0){
			
			updateTH(cloneSelect);
			
		}else{

            var cloneClasses = origTable.attr("class");
            var fixedHead = "<div id='"+cloneID+"div' class='hidden' style='display:block; overflow:hidden; border:1px solid "+origTable.css("border-right-color")+";'>";
            fixedHead += "<table id='"+cloneID+"' class='"+cloneClasses+"' style='width:"+origTable.width()+"px;'></table></div>";


            $("body").append(fixedHead);
			
			$(cloneSelect).css("border-bottom-width","1px");

			//clone the header rows from the original table
			var clone = origTable.children("thead").clone();
            clone.appendTo(cloneSelect);
			
			//insert the cloned header rows into the DOM
            clone = origTable.children("tbody").clone();
			clone.appendTo(cloneSelect);

			//orig table head height
			var origTableHeadHeight = origTable.children("thead").height();

			//initialize the cloned header to be fixed at the top of the page
			var pos = origTable.offset();
			$(cloneSelectDiv).css(
					{
						position: "fixed",
						marginLeft: "0px",
			            marginTop: "0px",
						top:"0px",
			            left:pos.left,
						"z-index": "1111",
						height: origTableHeadHeight
					}
			);
			
			//when the page is loaded for the first time
			//reposition/hide/show the header if needed
			moveCloneHead(cloneSelectDiv,true);
			
			//when the page scrolls
			$(window).scroll(function(){
				//reposition/hide/show the header if needed
				moveCloneHead(cloneSelectDiv);
			});
			
			//when the page is resized
			$(window).resize(function(){
				//reposition/hide/show the header if needed
				moveCloneHead(cloneSelectDiv,true);
			});
			
		}
	}


    /**
     * move the cloned table head so that it stays at the top of the browser window on
     * top of the corresponding table if the table is visible
     *
     * @param cloneSelect string that can be used in jQuery's select
     *                    statement $(). it should select the cloned table that
     *                    is being used for the scrollable table head
     * @param resize boolean
     */
	function moveCloneHead(cloneSelect,resize){
		if(origTable.filter(":visible").length >= 1){
			//document all the offsets and heights of the cloned header and the original table
			var origPos = origTable.offset();
			var origHeight =  origTable.height();
			var posFixed = $(cloneSelect).offset();
			var fixedHeadHeight = $(cloneSelect).height();
			var windowLeft = $(window).scrollLeft();
			var windowTop = $(window).scrollTop();
			
			
			//origTable block of code makes sure the cloned header is still the same size as the original header
			//for instance, it could be off due to the table initially being hidden
			var a = $(cloneSelect).width(); 
			var b = origTable.width();
			if(Math.abs(a-b) > 1 || resize == true){
				$(cloneSelect).css('width',origTable.width());
				//for each header cell, copy the height and width values
				//into the respective cloned header cell's height and min-width attributes respectively
				var origTH = origTable.children("thead").children("tr").children("th");
				$(cloneSelect+">thead>tr>th").each(function(i,val) {
				//this loop is very js intensive since HTML is being constantly edited
				//(phones cause this loop to lag, the current position of the browser)
					//to simulate good performance, check current position of table every time
				 	origPos = origTable.offset();
				 	posFixed = $(cloneSelect).offset();
				 	origHeight =  origTable.height();
					if(!(origPos.top < posFixed.top && origPos.top + origHeight > posFixed.top)){
						$(cloneSelect).addClass("hidden");
						return;
					}
					
					
				    //calculate new height
				    var newHeight = origTH.eq(i).height();
				    //change height
				    $(origTable).height(newHeight);
				    
				    //chrome doesn't consistently set the height correctly
                    //this while loop corrects height for chrome
                    //var z makes sure it doesn't loop forever
                    //some browsers may always return 0 for the height, which is never the case
                    //so skip this if height == 0
                    var z = 2;
				    while(origTable.height() != newHeight && z > 0 && origTable.height() != 0){
				    	z--;
				        var heightDiff = newHeight - origTable.height();
				        origTable.height(newHeight + heightDiff);
				    };
					//calculate new width
					var newWidth = origTH.eq(i).width();
					//change width
				    origTable.css("min-width", newWidth);
                    origTable.css("max-width", newWidth);
                    origTable.css("width", newWidth);
                    
                    //chrome doesn't consistently set the width correctly
                    //this while loop corrects width for chrome
                    //var z makes sure it doesn't loop forever
                    //some browsers may always return 0 for the width, which is never the case
                    //so skip this if width == 0 
                    z = 2;
					while(origTable.width() != newWidth && z > 0 && origTable.width() != 0){
                        z--;
					    var widthDiff = newWidth - origTable.width();
					    
					    origTable.css("min-width", newWidth+widthDiff);
                        origTable.css("max-width", newWidth+widthDiff);
                        origTable.css("width", newWidth+widthDiff);
					}
					
				});
			}
			
			
			//make sure the left offset of the clone matches the left offset of the original table
			if(origPos.left != windowLeft + posFixed.left){
				var leftDiff = 0 -(windowLeft - origPos.left);
				$(cloneSelect).css("left", leftDiff+"px");
			}
			
			//if the original table's header rows are above the browser window, but the bottom of the table is still viewable in the browser window
			if(origPos.top < posFixed.top && origPos.top + origHeight > posFixed.top){
				//diff < 0 when the bottom of the original table is above the bottom of the cloned header
				var diff = origPos.top + origHeight - windowTop;
				diff = diff - fixedHeadHeight;
				if(diff <= 0){
					//show the cloned header slightly above the browser window
					$(cloneSelect).css("top", diff+"px");
					$(cloneSelect).show();
				}else{
					//show the cloned header at the top of the browser window
					$(cloneSelect).css("top", "0px");
					$(cloneSelect).show();
				}
			}else{
				//hide the cloned header
				$(cloneSelect).hide();
			}
		}else{
			//hide the cloned header
			$(cloneSelect).hide();
		}
	}
	
	/**
	 * update the width and height of each th element in the cloned (scrollable) header based on the
	 * current dimensions of the corresponding th elements in the given table 
	 * 
	 * @param (string) cloneSelect - string that can be used in jQuery's select 
	 * statement $(). it should select the cloned table that is being used for the
	 * scrollable table head
	 * 
	 */
	function updateTH(cloneSelect){
		$(cloneSelect).css('width',origTable.width());
		
		
		//for each header cell, copy the height and width values
		//into the respective cloned header cell's height and min-width attributes respectively
		var origTH = origTable.find(" th");
		$(cloneSelect+" th").each(function(i,val) {
			origTable.height(origTH.eq(i).height());
			var width = origTH.eq(i).width();
			origTable.css("min-width", width);
			origTable.css("max-width", width);
			origTable.css("width", width);
		});
		
		//initialize the cloned header to be fixed at the top of the page
		var pos = origTable.offset();
		var temp = 1;
		$(cloneSelect).css({position: "fixed", marginLeft: "0px", marginTop: "0px", 
						    top:"0px", left:pos.left, "z-index": "110"});
		
		//when the page is loaded for the first time
		//reposition/hide/show the header if needed
		moveCloneHead(cloneSelect,true);
		
	}
	
	/**
	 * called once to initialize scrolling ability of the left most column on the given table.
	 * the left most column will then scroll with the browser window so that it will always be
	 * visible, if the given table is visible.
	 * 
	 */
	function scrollTableLColumn(){
		//setup the cloned header
		var cloneID = origTable.attr("id")+"STLC";
		var cloneSelect = "#"+cloneID;
		
		if(remove){
			//remove the scrolling left column and return
			$(cloneSelect).remove();
			return;
		}
		
		var cloneClasses = origTable.attr("class");
		var twidth = origTable.outerWidth();
		
		var wide = 0;
		origTable.children("tbody").children("tr").children("td:first-child").each(function(){
			if(wide == 0){
				wide = origTable.innerWidth();
			}
		});
		origTable.children("thead").children("tr").children("th:first-child").each(function(){
			var wideTH = origTable.outerWidth();
			if(wide < wideTH){
				wide = wideTH;
			}
		});
		
		//insert the cloned header into the DOM
		//or update the table header if the header already exists
		if($(cloneSelect).length != 0){
			$(cloneSelect).width(twidth);
			$(cloneSelect+'div').css("width",wide+"px");
		}else{
		
			var fixedCol = "<div id='"+cloneID+"div' style='display:none; overflow:hidden; border:1px solid "+origTable.css("border-right-color")+";'>";
			fixedCol += "<table id='"+cloneID+"' class='"+cloneClasses+"' style='padding:0px; background: white; width:"+twidth+"px";
			fixedCol += "; border-bottom-width:"+origTable.css("border-bottom-width")+"; top: -1px;'></table></div>";
			
			//insert the cloned header into the DOM
			$("body").append(fixedCol);
				
			//clone the header column from the original table
			var orig = origTable.children("thead");
			var clone = orig.clone();
			//insert the cloned header rows into the DOM
			clone.appendTo(cloneSelect);
			
			//clone the body from the original table
			var orig = origTable.children("tbody");
			var clone = orig.clone();
			//insert the cloned header rows into the DOM
			clone.appendTo(cloneSelect);
			
			
			$(cloneSelect+'div').css("width",wide+"px");
			
			//initialize the cloned header to be fixed at the left side of the page
			var pos = origTable.offset();
			var temp = 1;
			$(cloneSelect+'div').css({position: "fixed", marginLeft: "0px", marginTop: "0px", top:"0px", left:"0px", "z-index": "100"});
			$(cloneSelect).css({"background-color": origTable.find(" th:nth-child(2)").css("background-color"), marginBottom: "0px" });
			
			//when the page is loaded for the first time
			//reposition/hide/show the header if needed
			moveCloneLColumn(cloneSelect+'div');
			
			//when the page scrolls
			$(window).scroll(function(){
				//reposition/hide/show the header if needed
				moveCloneLColumn(cloneSelect+'div');
			});
			
			//when the page is resized
			$(window).resize(function(){
				//reposition/hide/show the header if needed
				moveCloneLColumn(cloneSelect+'div');
			});
		}	
	}
	
	
	/**
	 * called every time a table's scrollable column needs to move.  this is typically done every
	 * time the browser is resized or scrolled
	 * 
	 * @param (string) cloneSelect - string that can be used in jQuery's select statement $().  it should select the parent div of the appropriate table's clone that acts as the scrollable column
	 */
	function moveCloneLColumn(cloneSelect){
		if(origTable.filter(":visible").length >= 1){
			//document all the offsets and heights of the cloned left column and the original table
			var origPos = origTable.offset();
			var origWidth =  origTable.width();
			var clonePos = $(cloneSelect).offset();
			var fixedColWidth = $(cloneSelect).width();
			var windowLeft = $(window).scrollLeft();
			var windowTop = $(window).scrollTop();
			var windowWidth = $(window).width();http://api.jquery.com/category/deprecated/deprecated-1.3/
			var windowHeight = $(window).height();
			
			//make sure the clone container is showing the right amount of table
			var wide = 0;	
			origTable.children("tbody").children("tr").children("td:first-child").each(function(){
				if(wide == 0){
					wide = origTable.outerWidth();
				}
			});
			origTable.children("thead").children("tr").children("th:first-child").each(function(){
				var wideTH = origTable.outerWidth();
				if(wide < wideTH){
					wide = wideTH;
				}
			});
			var widetemp = $(cloneSelect+">table>thead>tr>th:first-child").outerWidth();
			
			if(cloneSelect == "#tablehoursAvailableInPersonSTLCdiv"){
				var temp1232123 = true;
			} 
			if(wide != widetemp){
				//fix the width
				$(cloneSelect+">table").css('width',origTable.outerWidth());
			/*
				//clone the header column from the original table
				var origHead = origTable.children"thead");
				var cloneHead = origHead.clone();				
				//clone the body from the original table
				var origBody = origTable.children("tbody");
				var cloneBody = origBody.clone();
				//replace the clone'd contents
				$(cloneSelect+">table").html(cloneHead);
				$(cloneSelect+">table").append(cloneBody);
			*/
				$(cloneSelect).css("width",wide);
			}
			 		
			//make sure the top offset of the clone matches the top offset of the original table
			if(origPos.top != clonePos.top){
				var topDiff = 0 -(windowTop - origPos.top);
				
				$(cloneSelect).css("top", topDiff+"px");
			}
			
			//if the original table's header rows are left of the browser window, but the bottom or top of the table is still viewable in the browser window
			if(origPos.left < windowLeft && origPos.left < windowLeft + windowWidth){
				//diff < 0 when the right side of the original table is left the right side of the cloned column
				var diff = origPos.left + origWidth - clonePos.left - fixedColWidth;
				//console.log(diff+" "+origWidth);
				
				if(diff < 0){
					//show the cloned column slightly left of the browser window
					$(cloneSelect).css("left", diff+"px");
					$(cloneSelect).show();
				}else{
					//show the cloned column at the left of the browser window
					$(cloneSelect).css("left", "0px");
					$(cloneSelect).show();
				}
				
			}else{
				//hide the cloned header
				$(cloneSelect).hide();
			}
		}else{
			//hide the cloned header
			$(cloneSelect).hide();
		}
	}
	
	
	/**
	 * is called to initialize a table's collapsible column functionality.  if any column has a data-ST-group
	 * attribute, then this column will only expand or collapse other columns that share this attribute and
	 * attribute value 
	 * 
	 * @param {Object} tableClass
	 * @param {Object} rowHead
	 * @param {Object} colHead
	 * @param {Object} colCclass
	 * @param {Object} colEclass
	 * 
	 */
	function collapsableColumns(tableClass,rowHead,colHead,colCclass,colEclass){
		//give the table's thead cells the cursor:pointer css styling so
		//that the user can easily tell that something happens when they click on it
		$("."+tableClass+">thead").css('cursor','pointer');
		//$("."+tableClass+" thead").attr('title','Hide/Unhide Some Table Rows');
		//$("."+tableClass+" thead").tooltip();
		$("."+tableClass+">thead").click(function(){
			var groupID = origTable.attr('data-ST-group');
			colCol(tableClass,rowHead,colHead,groupID,colCclass,colEclass);
		});
		
		//if it exists, enable a 'collapse/expand all' element 
		$('*[data-super-table="'+origTable.attr('id')+'"]').click(function(){
			var groupID = false;
			colCol(tableClass,rowHead,colHead,groupID,colCclass,colEclass);
		});
		
	}
	
	/**
	 * is called when columns needs to be expanded or callapsed
	 * 
	 * @param {Object} tclass
	 * @param {Object} rowHead
	 * @param {Object} colHead
	 * @param {Object} groupID
	 * @param {Object} colCclass
	 * @param {Object} colEclass
	 */
	function colCol(tclass,rowHead,colHead,groupID,colCclass,colEclass){
		var groupAttr = '';
		if(groupID){
			var groupAttr = "[data-ST-group='"+groupID+"']";
		}
		
		//when columns are expanded or collapsed, there may be a class
		//that should be applied to each state (could be used for CSS purposes/etc.)
		var swapClasses = false;
		if(colCclass != colEclass && colCclass != '' && colEclass != ''){
			swapClasses = true;
		}
		//the columns are hidden and should be shown
		if($("."+tclass+" .columnHideable"+groupAttr).css('display') != 'none'){
			//hide the columns
			$("."+tclass+" .columnHideable"+groupAttr).css('display','none');
			
			//adjust colspans as neccessary
			$("."+tclass+" "+groupAttr+"[data-colspanmin]").each(function(){
				var colMin = origTable.attr("data-colspanmin");
				origTable.attr('colspan',colMin);
			});
			
			//swap classes if desired
			if(swapClasses){
				$("."+tclass+" ."+colEclass+groupAttr).removeClass(colEclass).addClass(colCclass);	
			}
		}
		// the columns are showing and need to be hidden
		else{
			//show the columns
			$("."+tclass+" .columnHideable"+groupAttr).css('display','');
			
			//adjust colspans as neccessary
			$("."+tclass+" "+groupAttr+"[data-colspanmax]").each(function(){
				var colMax = origTable.attr("data-colspanmax");
				origTable.attr('colspan',colMax);
			});
			
			//swap classes if desired
			if(swapClasses){
				$("."+tclass+" ."+colCclass+groupAttr).removeClass(colCclass).addClass(colEclass);	
			}
		}
		ST(rowHead,colHead);
	}
	
	/**
	 * is called to initialize a table's collapsable row functionality.  if any row has a data-ST-group
	 * attirbute, then this row will only expand or collapse other rows that share this attribute and
	 * attribute value 
	 * 
	 * @param {Object} tableClass
	 * @param {Object} rowHead
	 * @param {Object} colHead
	 * @param {Object} rowCclass
	 * @param {Object} rowEclass
	 * 
	 */
	function collapsableRows(tableClass,rowHead,colHead,rowCclass,rowEclass){
		//give the first column of each row the cursor:pointer css styling so
		//that the user can easily tell that something happens when they click on it 
		$("."+tableClass+">tbody>tr>td:first-child").css('cursor','pointer');
				
		// expand/collapse the appropriate ST-group of rows
		$("."+tableClass+">tbody>tr>td:first-child").click(function(){
			var groupID = origTable.parent().attr('data-ST-group');
			rowCol(tableClass,rowHead,colHead,groupID,rowCclass,rowEclass);
		});
		
		//if it exists, enable a 'collapse/expand all' element 
		$('*[data-super-table="'+origTable.attr('id')+'"]').click(function(){
			var groupID = false;
			rowCol(tableClass,rowHead,colHead,groupID,rowCclass,rowEclass);
		});
	}
	
	/**
	 * is called when a row or group of rows needs to be expanded or collapsed
	 * 
	 * @param {Object} tclass
	 * @param {Object} rowHead
	 * @param {Object} colHead
	 * @param {Object} groupID
	 * @param {Object} rowCclass
	 * @param {Object} rowEclass
	 */
	function rowCol(tclass,rowHead,colHead,groupID,rowCclass,rowEclass){
		
		//when rows are expanded or collapsed, there may be a class
		//that should be applied to each state (could be used for CSS purposes/etc.)
		var swapClasses = false;
		if(rowCclass != rowEclass && rowCclass != '' && rowEclass != ''){
			swapClasses = true;
		}
		//a table can have multiple groups of rows that expand and collapse
		//independently if this is the case, then expand/collapse the appropriate
		//group of rows
		if(groupID){
			//show the row group's hidden rows
			if($("."+tclass+" .rowHideable[data-ST-group='"+groupID+"']").hasClass("hidden")){
				$("."+tclass+" .rowHideable[data-ST-group='"+groupID+"']").removeClass("hidden");
				//swap classes if desired
				if(swapClasses){
					$("."+tclass+" [data-ST-group='"+groupID+"'] ."+rowCclass).removeClass(rowCclass)
					                                                          .addClass(rowEclass);
				}
			}else{
				//hide the row group's hide-able rows
				$("."+tclass+" .rowHideable[data-ST-group='"+groupID+"']").addClass("hidden");
				//swap classes if desired
				if(swapClasses){
					$("."+tclass+" [data-ST-group='"+groupID+"'] ."+rowEclass).removeClass(rowEclass)
					                                                          .addClass(rowCclass);
				}
			}
		}
		//there are no row groups in this table or no group was given. 
		//all rows should be expanded/collapsed together 
		else{
			if($("."+tclass+" .rowHideable").hasClass("hidden")){
				//show the hidden rows
				$("."+tclass+" .rowHideable").removeClass("hidden");
				//swap classes if desired
				if(swapClasses){
					$("."+tclass+" ."+rowCclass).removeClass(rowCclass).addClass(rowEclass);
				}
			}else{
				//hide the hideable rows
				$("."+tclass+" .rowHideable").addClass("hidden");
				//swap classes if desired
				if(swapClasses){
					$("."+tclass+" ."+rowEclass).removeClass(rowEclass).addClass(rowCclass);
				}
			}	
		}
		//make sure the scrolling column and thead still line up ok
		ST(rowHead,colHead);
	}
	
	
	//clone css properties from the jQuery Object orig to clone
	function cloneObjectCSS($clone,$orig){
		var styles = new Array(
		              "border-top-width","border-top-color","border-top-style",
				      "border-left-width","border-left-color","border-left-style",
				      "border-right-width","border-right-color","border-right-style",
				      "border-bottom-width","border-bottom-color","border-bottom-style",
				      "background-color");
		
		for(var i in styles){
		 	// $clone.css(styles[i],$orig.css(styles[i]));
		}
	}
	
	function removeST (clone) {

    }
	
}

}( jQuery ));