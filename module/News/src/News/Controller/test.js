// 1 js file for all  =>  HO/HA/UP market + VN/EN/JP language

http://priceboard.fpts.com.vn/ho4/VN/get.asp?a=1
	
//ngocta2
var BrowserDetect = {
	init: function () {
		this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
	},
	searchString: function (data) {
		for (var i=0;i<data.length;i++)	{
			var dataString = data[i].string;
			var dataProp = data[i].prop;
			this.versionSearchString = data[i].versionSearch || data[i].identity;
			if (dataString) {
				if (dataString.indexOf(data[i].subString) != -1)
					return data[i].identity;
			}
			else if (dataProp)
				return data[i].identity;
		}
	},
	dataBrowser: [
		{
			string: navigator.userAgent,
			subString: "Firefox",
			identity: "FF"
		},
		{
			string: navigator.userAgent,
			subString: "MSIE",
			identity: "IE",
			versionSearch: "MSIE"
		}
	]

};



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



// click vao ten ma = check box, ko can click vao checkbox
// a='A','B','C' .... hien cac ck co ky tu dau = a,b,c....
// a='1' hien cac CCQ
// a='0' hien tat ca CK + CCQ
function ViewByAlphabet(i)
{
//alert('dd')
	if(i.innerHTML == 'Xem tất' || i.innerHTML == "View All" || i.innerHTML == "全銘柄")
	{
		var j;
		for(j=65; j<=90; j++)
		{
			document.getElementById("char" + j).className = "chk";
		}
		i.className = "chkh";

		var k;
		for(k=1; k<=total; k++)
		{
			var chkid = "c_" + k;
			var spnid = "s_" + k;
			document.getElementById(chkid).style.display = '';
			document.getElementById(spnid).style.display = '';
		}
		document.getElementById("checkAll").style.display = '';		
	}
	else if(i.innerHTML == 'CCQ' || i.innerHTML == 'FC')
	{
		document.getElementById("divCheckBox").innerHTML = document.getElementById("divCheckBox").innerHTML.replace(/<BR>/i,"");
		var j;
		for(j=65; j<=90; j++)
		{
			document.getElementById("char" + j).className = "chk";
		}
		document.getElementById("viewAll").className = "chk";
		i.className = "chkh";
		
		var k;
		for(k=1; k<=total; k++)
		{
			var chkid = "c_" + k;
			var spanid = "s_" + k;
			if(document.getElementById(chkid).getAttribute("t") == 2)
			{
				document.getElementById(chkid).style.display = '';
				document.getElementById(spanid).style.display = '';
			}
			else
			{
				document.getElementById(chkid).style.display = 'none';									
				document.getElementById(spanid).style.display = 'none';
			}
		}
		document.getElementById("checkAll").style.display = '';
	}
	else
	{	
		var j;
		for(j=65; j<=90; j++)
		{
			document.getElementById("char" + j).className = "chk";
		}
		document.getElementById("viewAll").className = "chk";
		i.className = "chkh";
		
		var k;
		for(k=1; k<=total; k++)
		{
			var chkid = "c_" + k;
			var spanid = "s_" + k;
			if(document.getElementById(chkid).getAttribute("t") == 1)
			{
				document.getElementById(chkid).style.display = '';
				document.getElementById(spanid).style.display = '';
				if(document.getElementById(spanid).innerHTML.substring(0,1) == i.innerHTML)
				{
					document.getElementById(chkid).style.display = '';
					document.getElementById(spanid).style.display = '';
				}
				else
				{
					document.getElementById(chkid).style.display = 'none';
					document.getElementById(spanid).style.display = 'none';
				}
			}
			else
			{
				document.getElementById(chkid).style.display = 'none';
				document.getElementById(spanid).style.display = 'none';
			}			
		}
		document.getElementById("checkAll").style.display = '';
	}
	
	document.getElementById("divCheckBox").style.display='';
}

function CheckAllIfVisible()
{
	for (var i=0; i < document.frmSelect.elements.length; i++)
		if (document.frmSelect.elements[i].type=='checkbox')
		{
			if(document.frmSelect.elements[i].style.display == '')
			{													
				document.frmSelect.elements[i].checked = true;				
				document.getElementById('s_'+document.frmSelect.elements[i].id.substring(2)).style.color = g_ccc;
			}
			else
			{
				document.frmSelect.elements[i].checked = false;				
				document.getElementById('s_'+document.frmSelect.elements[i].id.substring(2)).style.color = g_ccu;
			}	
		}
}

function ClearAll()
{
	for (var i=0; i < document.frmSelect.elements.length; i++)
	{
		if (document.frmSelect.elements[i].type)
		{
			document.frmSelect.elements[i].checked = false;		
			document.getElementById('s_'+document.frmSelect.elements[i].id.substring(2)).style.color = g_ccu;
		}
	}
}





// an nut "XEM THEM"
function doAddCode(){
	LoadSelectList(g_ckn);
	var i,j;
	for(j=1; j<=total; j++)
	{
		var chkid = "c_" + j;
		var spnid = "s_" + j;
		document.getElementById(chkid).style.display = '';								
		document.getElementById(spnid).style.display = '';
	}
	document.getElementById("checkAll").style.display = '';
	
	document.getElementById("divSelect").style.display = '';
	document.getElementById("divCheckBox").style.display = '';	
}




function GetExpiryDate(DayCount)
{
	var UTCstring;
	Today = new Date();
	nomilli=Date.parse(Today);
	Today.setTime(nomilli+DayCount*24*60*60*1000);
	UTCstring = Today.toUTCString();
	return UTCstring;
}
	
function SetCookie(name,value,duration)
{
	CookieString=name+"="+escape(value)+";EXPIRES="+GetExpiryDate(duration);
	document.cookie=CookieString;
}

function GetCookie(CookieName) 
{
	var CookieString=""+document.cookie;
	var index1=CookieString.indexOf(CookieName);
	if (index1==-1 || CookieName=="") return ""; 
	var index2=CookieString.indexOf(';',index1);
	if (index2==-1) index2=CookieString.length; 
	return unescape(CookieString.substring(index1+CookieName.length+1,index2));
}

function BuildSelectList(RemoveComma)
{
	var strSelectList="";
	for (var i=0; i < document.frmSelect.elements.length; i++)
		if (document.frmSelect.elements[i].type == 'checkbox')
			if (document.frmSelect.elements[i].checked /*&& document.frmSelect.elements[i].style.display==''*/)
				strSelectList += "," + document.frmSelect.elements[i].value;
				
	if (RemoveComma)	strSelectList=strSelectList.substring(1);
	return strSelectList;
}

function SaveSelectList(CookieName)
{
	CODE_LIST=BuildSelectList(0)+',';	
	SetCookie(CookieName, CODE_LIST, g_ckd);
}

function LoadSelectList(CookieName)
{
	var str=GetCookie(CookieName);
	for (var i=0; i < document.frmSelect.elements.length; i++)
		if (document.frmSelect.elements[i].type == 'checkbox')
			if (str.indexOf(","+document.frmSelect.elements[i].value)>=0)
			{
				document.frmSelect.elements[i].checked = true;
				document.getElementById('s_'+document.frmSelect.elements[i].id.substring(2)).style.color = g_ccc;
			}	
}

// click event function, user click chuot trai vao 1 tr 
function c(r)
{
	var i=0, m=r.cells.length, MakeNormal=false;
	
	if(r.cells[0].style.backgroundColor == g_cbhr1 || r.cells[0].style.backgroundColor == g_cbhr2)
		MakeNormal=true; 
	else
		MakeNormal=false;
		
	for (var j=0; j<m; j++) r.cells[j].style.backgroundColor = (MakeNormal?g_cbb:g_cbhr1); 
	
	if (MakeNormal)
	{	
	r.cells[1].style.backgroundColor = g_cbg;
	r.cells[2].style.backgroundColor = g_cbg;
	r.cells[3].style.backgroundColor = g_cbg;
	r.cells[10].style.backgroundColor = g_cbg;
	r.cells[11].style.backgroundColor = g_cbg;
	r.cells[12].style.backgroundColor = g_cbg;	
	r.cells[19].style.backgroundColor = g_cbg;
	r.cells[20].style.backgroundColor = g_cbg;	
	r.cells[21].style.backgroundColor = g_cbg;
	r.cells[22].style.backgroundColor = g_cbg;
	r.cells[23].style.backgroundColor = g_cbg;
	r.cells[24].style.backgroundColor = g_cbg;
	r.cells[25].style.backgroundColor = g_cbg;
	}
}

function HideAllCheck()
{
	for(var i=1; i<=total; i++)
	{											
		document.getElementById("c_"+i).style.display='none';
		document.getElementById("s_"+i).style.display='none';
	}
	for(j=65; j<=90; j++){
		document.getElementById("char" + j).className = "chk";
	}
	document.getElementById("viewAll").className = "chk";
	document.getElementById("checkAll").style.display='none';
}

function doView()
{
	SaveSelectList(g_ckn);
	HideAllCheck();
	document.getElementById("divCheckBox").style.display='none';
	AjaxRequestGet(1); // get new code list 
}


// GET faster than POST 10 times
function AjaxRequestGet(Action)
{	
	AjaxRequest.get(
		{
		'url':g_Language+'/get.asp?a='+Action+'&sid='+g_SID+'&lpt='+g_LPT
		,'onSuccess':function(req){ProcessResponse(req.responseText,Action);}
		,'timeout':10000
		,'onTimeout':function(req){ g_tc--;}
		,'onError':function(req){alert('Kết nối mạng bị đứt');}
		}
	)
}

// switch case
function ProcessResponse(responseText, Action)
{
	if(Action==1) UpdateNewCodeList(responseText);
	if(Action==2) Update4G(responseText);
	g_tc--;
}


// update stock quote with new codes
function UpdateNewCodeList(responseText)
{	
	// hide temporary
	document.getElementById("tdra").style.display='none';

	// fill all HTML header + HTML row of all code
	document.getElementById("tdra").innerHTML = responseText;

	// move uncheck row
	RemoveUncheckCode();
	
	// show again
	document.getElementById("tdra").style.display='';
		
	// show favourites codes	
	InitDblClick();	
}

// 1) user select again code list 
// 2) get all HTML of all code from server
// 3) remove uncheck code
function RemoveUncheckCode()
{
	// remove row of code which in uncheck state
	var arrCode=new Array(), j=2;
	// get state
	for (var i=0; i < document.frmSelect.elements.length; i++)
		if (document.frmSelect.elements[i].type == 'checkbox')
			if (document.frmSelect.elements[i].checked) arrCode[j++]=1; else arrCode[j++]=0	;		
	// remove row
	for (var i=document.getElementById('FPTSQuote').rows.length-1;i>=0+2;i--)
		if (arrCode[i]==0) document.getElementById('FPTSQuote').deleteRow(i);
}

function d(tr)
{
	GoTop(tr, true);
}





function InitDblClick()
{
	setTimeout(function()
	{
		try
		{
			g_arrFavouriteCodeID = new Array();	// reset array
			g_tbl=document.getElementById('FPTSQuote');	// table object
			if (!g_tbl.rows[g_hrc]) return false;
			g_trFirstID=g_tbl.rows[g_hrc].id;	// first tr id
		
			LoadFavCodeFromCookie();
		}
		catch(e){}	
	},000);	
}




function SaveFavCodeToCookie()
{
	var vTemp='';
	for (var i=0; i<g_arrFavouriteCodeID.length; i++)
		vTemp+=','+g_arrFavouriteCodeID[i]
	SetCookie(g_cknfc, vTemp.substring(1), g_ckd);
}

function LoadFavCodeFromCookie()
{
	var vTemp=GetCookie(g_cknfc);
	var arrFavCode=new Array();
	arrFavCode=vTemp.split(',');
	
	for (var i=0; i<arrFavCode.length; i++)
		if(arrFavCode[i]!='')
			GoTop(document.getElementById(arrFavCode[i]), false);
	
}


// c1 = border-bottom none
// c2 = border-bottom bold
function SetBorder(c, tr)
{
	if (!tr) return false;
	for (var j=0; j<tr.cells.length; j++)	
	{
		if (c=='c2')
		{
			tr.cells[j].style.borderBottom="#00FF00 3px solid";
			tr.style.borderBottom="#00FF00 3px solid";
		}	
		if (c=='c1')
		{
			tr.cells[j].style.borderBottom="#505050 1px solid";
			tr.style.borderBottom="#505050 1px solid";
		}	
	}	
}



// timer procedure
function TimerProc()
{
	if(g_SE=='1') return false;
	
	var v = (new Date());
	var h, m, s;
	h = v.getHours();
	m = v.getMinutes();
	s = v.getSeconds();
	document.getElementById('Clock').innerHTML = "".concat((h < 10) ? '0'.concat(h) : h).concat(':').concat((m < 10) ? '0'.concat(m) : m).concat(':').concat((s < 10) ? '0'.concat(s) : s);	

	// request data update		
	g_cd--;
	if (g_cd<=0)  
	{
		SendRequest();	
		g_cd=g_it;	
	}
}

// only 1 thread submit -> get data change
function SendRequest()
{
	if (g_tc>0) return false;
	g_tc++;	
	AjaxRequestGet(2);	// get data change
}


function GoTop(tr, vSave)
{
	if (!tr) return false;

	var i,j,k;
	var rowFirst=g_tbl.rows[g_hrc];
	
	// move dong ve vi tri cu + set border
	if(tr.rowIndex<=g_arrFavouriteCodeID.length+g_hrc)
	{
		for (i=0; i<g_arrFavouriteCodeID.length; i++)
		{
			if (tr.id==g_arrFavouriteCodeID[i])
			{
				// set border - chi set lai border neu user dblclick vao tr la row cuoi cua group TOP
				if(tr.id==g_arrFavouriteCodeID[0])
				{					
					SetBorder(g_cnbb, document.getElementById(g_arrFavouriteCodeID[1]));
				}
				//console.info(g_arrFavouriteCodeID,g_hrc+g_arrFavouriteCodeID.length);
				for (j=g_hrc+g_arrFavouriteCodeID.length; j<g_tbl.rows.length; j++)
				{					
					try
					{
						if (tr.id==g_trFirstID)
						{
							//tra ve cho cu - neu la tr dau tien
							g_tbl.rows[j].parentNode.insertBefore(tr,g_tbl.rows[j]);							
							SetBorder(g_cnbn, tr);
							
							// xoa data tai mang
							g_arrFavouriteCodeID.splice(i,1);	
							if (vSave) SaveFavCodeToCookie();
							return;							
						}								
						else if (g_tbl.rows[j].id>tr.id && j==g_hrc+g_arrFavouriteCodeID.length)
						{
							//tra ve cho cu - neu la tr dau tien
							g_tbl.rows[j].parentNode.insertBefore(tr,g_tbl.rows[j]);
							SetBorder(g_cnbn, tr);
							
							// xoa data tai mang
							g_arrFavouriteCodeID.splice(i,1);	
							if (vSave) SaveFavCodeToCookie();
							return;		
						}
						else if (g_tbl.rows[j].id<tr.id && g_tbl.rows[j+1].id>tr.id)
						{
							//tra ve cho cu - neu la tr o giua
							g_tbl.rows[j+1].parentNode.insertBefore(tr,g_tbl.rows[j+1]);												
							SetBorder(g_cnbn, tr);
							
							// xoa data tai mang
							g_arrFavouriteCodeID.splice(i,1);	
							if (vSave) SaveFavCodeToCookie();							
							return;							
						}	
					}
					catch(e)
					{
						//console.info('j=%s, e=%s',j, e);
						//tra ve cho cu - neu la tr cuoi cung -> ko the InsertBefore>>dung appendChild
						//document.getElementById('tBody').appendChild(tr);
						g_tbl.tBodies[0].appendChild(tr);
						SetBorder(g_cnbn, tr);
						
						// xoa data tai mang
						g_arrFavouriteCodeID.splice(i,1);	
						if (vSave) SaveFavCodeToCookie();
						return;
					}					
				}
			}
		}	
	}	
	
	// move row len dong dau tien
	rowFirst.parentNode.insertBefore(tr,rowFirst);	
	
	// ghi lai ID cua row
	g_arrFavouriteCodeID[g_arrFavouriteCodeID.length]=tr.id;
	
	// bold border ca tr neu la favourite code dau tien
	// khi g_arrFavouriteCodeID.length=1
	if (g_arrFavouriteCodeID.length==1)
		SetBorder(g_cnbb,tr);
		
	if (vSave) SaveFavCodeToCookie();
}

function ProcessHeaderWidth()
{
	try
	{
		if (!document.getElementById('FPTSQuote')) return false;

		for (var i=0;i<2;i++){
			for (var j=0;j<30;j++){
				if (document.getElementById('FPTSQuote').rows[i].cells[j]){
					iClientWidth = parseInt(document.getElementById('FPTSQuote').rows[i].cells[j].clientWidth);
					iOffsetWidth = parseInt(document.getElementById('FPTSQuote').rows[i].cells[j].offsetWidth);
					document.getElementById('FPTSHeader').rows[i].cells[j].style.width = iClientWidth + 'px';
				}
			}
		}
		
		// FF3 - lech Left qua nhieu => chi chinh lai Left neu chenh lech qua +/- 5 pixel
		if(document.getElementById('FPTSQuote') && document.getElementById('divHeader'))
		{
			var ScreenWidth=document.body.clientWidth;
			var TableHeight=document.getElementById('FPTSQuote').offsetHeight;
			var ScreenHeight=document.body.clientHeight;
			var vLeft=(ScreenWidth-1000)/2;
			var objDiv=document.getElementById('divHeader');
			var Offset=2;
			objDiv.style.left=vLeft;	
			
			if(BrowserDetect.browser=='IE')
				if (TableHeight<ScreenHeight)
					objDiv.style.left=(ScreenWidth-1000-16)/2;
		}		
	}
	catch(err)
	{
		
	}	
}


function TopFixed(ele,dist) 
{	
	if (!document.getElementById('divHeader')) return false;
	if (!document.getElementById('FPTSHeader')) return false;
	if (!document.getElementById('FPTSQuote')) return false;
	
	var fBrw=(navigator.userAgent.indexOf('MSIE')!= -1 && navigator.userAgent.indexOf('Windows')!= -1);

    if(fBrw) //IE
	{
		//alert('IE');
		divid='divHeader';
		document.getElementById('divHeader').style.position='absolute';
	
		var eY=0;
		var startX, startY;
		if(document.body.clientWidth < 1000){
			startX = - 1;
		}
		else{
			startX = (document.body.clientWidth - 1000) / 2 - 1;
		}
		startY = 0;

		window.stayFloat=function(ftlObj)
		{
			iTop=parseInt(itGetXY(document.getElementById('FPTSQuote'))[1]);
			var startX, startY;
			var ns = (navigator.appName.indexOf("Netscape") != -1);
			if(document.body.clientWidth < 1000){
				startX = - 1;
			}
			else{
				startX = (document.body.clientWidth - 1000) / 2 - 1;
			}
			if (document.body.scrollTop < iTop) {
				ftlObj.style.display = 'none';
			} 
			else {
				ftlObj.style.display = '';			
				ftlObj.style.top= document.body.scrollTop;
				ftlObj.style.left=(document.body.clientWidth - 1000) / 2 - 1;
			}
			setTimeout(function(){stayFloat(ftlObj,eY)}, 500);
		}

		var ftlObj = document.getElementById?document.getElementById(divid):document.all?d.all[divid]:document.layers[divid];
		if(!ftlObj) return;
		ftlObj.x = startX;
		ftlObj.y = startY;
		stayFloat(ftlObj);
		
		return true;	
			
    } 
	else //FF
	{	
		
		//alert('FF');
		ele.style.top = dist+'px';
		setInterval(function(ele) {
			ele.style.top = dist+'px';
			iTop=parseInt(itGetXY(document.getElementById('FPTSQuote'))[1]);
			if (document.body.scrollTop < iTop)
				ele.style.display = 'none';		
			else			
				ele.style.display = '';	
		},10,ele);	
	}
} 

function itGetXY(a,offset) {
	var p=offset?offset.slice(0):[0,0];
	while(a) {
		p[0]+=a.offsetLeft;
		p[1]+=a.offsetTop;
		if (a.tagName.toUpperCase()=="BODY") break;
		a=a.offsetParent;
	}
	return p;
}

function ChangeColor(id)
{
	var td=document.getElementById(id);
	if (td)
		if (td.style.color.toUpperCase()=='#FF7D00' || td.style.color=='rgb(255, 125, 0)')
			td.style.color='#00FF00';
		else
			td.style.color='#FF7D00';
}

function BottomFixed(ele,dist) 
{	
	if (!document.getElementById('divFooter')) return false;
	if (!document.getElementById('FPTSHeader')) return false;
	if (!document.getElementById('FPTSQuote')) return false;
	
	var fBrw=(navigator.userAgent.indexOf('MSIE')!= -1 && navigator.userAgent.indexOf('Windows')!= -1);
	
    if(fBrw) //IE
	{		
		document.getElementById('divFooter').style.left=(document.body.clientWidth-(558+442))/2
		document.getElementById('divFooter').style.top=(parseInt(document.body.scrollTop+document.body.clientHeight)-69);
		window.onscroll = scroll;
		
		function scroll()
		{
			if (document.body.scrollTop>0)document.getElementById('divFooter').style.display='';
			document.getElementById('divFooter').style.position='absolute';
			document.getElementById('divFooter').style.top=(parseInt(document.body.scrollTop+document.body.clientHeight)-69);
		}
    } 
	else //FF
	{	
		document.getElementById('divFooter').style.left=(document.body.clientWidth-(558+442))/2
		document.getElementById('divFooter').style.display='';
		dist=document.body.clientHeight-document.getElementById('divFooter').style.height-69;
		ele.style.top = dist+'px';
		setInterval(function(ele) {			
			ele.style.top = dist+'px';
		},10,ele);	
	}
	
} 

function l(a,c)
{
	if (a==1) window.open(g_kvn+c,c,g_wprop);
	if (a==2) window.open(g_ken+c,c,g_wprop);
}

// update bang gia
function Update4G(SERVER_DATA)
{
    //console.info(SERVER_DATA);
    //if (SERVER_DATA=='') return false;
    
    /********************* g_SID *******************/	
	if (SERVER_DATA==g_scr)// client snapshotID = 'reload'
	{		
		setTimeout(function(){document.location=g_rurl;},2000)
		return false;
	}	
	if (SERVER_DATA==g_sce) g_SE='1';
	if (SERVER_DATA.length>=5)	g_SID=Right(SERVER_DATA,5);
	/********************* /g_SID *******************/
	    
    /************ UPDATE MARKET STATUS (HO/HA/UP) **********/	
	if (SERVER_DATA.indexOf('[')!=-1)
	{
		var arrMarketStatus = new Array(); 
		arrMarketStatus = SERVER_DATA.split('[');
		
		for (var i=0; i<arrMarketStatus.length; i++)
		{
			var temp=Left(arrMarketStatus[i],arrMarketStatus[i].indexOf(']'));
			var cc=temp.substring(2);
			var ce=temp.substring(0,2);
			if (cc!='')
			{
				var ms=GetMarketStatus(ce,cc,g_Language);//
				UpdateCell(document.getElementById(g_idms),ms,'mar','p');
			}
		}	
	}		
    /************ /UPDATE MARKET STATUS (HO/HA/UP) **********/
    
    /************ UPDATE INDEX (HO/HA/UP) **********/
	for (var x=0; x<g_arrCNL.length;x++)
	{
		if (SERVER_DATA.indexOf('('+g_arrCNL[x])!=-1)
		{
			var MARKET_INFO;
			MARKET_INFO=Right(SERVER_DATA,SERVER_DATA.length-SERVER_DATA.indexOf('('+g_arrCNL[x])-4);
			MARKET_INFO=Left(MARKET_INFO,MARKET_INFO.indexOf(')'));
			////////console.info('MARKET_INFO_HCM=%s', MARKET_INFO);	
			
			var arrMarketInfo = new Array(); // mar_, maru, marn, mard
			arrMarketInfo = MARKET_INFO.split('|');
			//return;
			for (var i=0; i<arrMarketInfo.length; i++)
			{
				////////console.info('i=%s arrMarketInfo[%s]=%s', MARKET_INFO, i, arrMarketInfo[i]);

				var c='n', cn2='';
				if (arrMarketInfo[i]=='')
					cn2=document.getElementById('tdM'+g_arrCNL[x]+'1').className;

				else		
				{
					var img=document.getElementById('imgM'+g_arrCNL[x]);
				
					if(parseFloat(arrMarketInfo[2])==0)	{c='n';};
					if(parseFloat(arrMarketInfo[2])>0)	{c='u';};
					if(parseFloat(arrMarketInfo[2])<0)	{c='d';};
					////////console.info('c2=%s',c);

					SetImage(img,c);    // update image

					if(i==0||i==7||i==8||i==9) c='_';
					cn2='mar'+c;
					
					//4,5,6,10,11,12 => 'mar' => do not change color of qtty, value, trade no, putthrough
					if (i==4||i==5||i==6||i==10||i==11||i==12) cn2='mar';
				}
						
				if (arrMarketInfo[i]!='')
				{
					if(document.getElementById('tdM'+g_arrCNL[x]+i))
					{
						if (document.getElementById('tdM'+g_arrCNL[x]+i).innerHTML!=arrMarketInfo[i])
						{
							
							UpdateCell(document.getElementById('tdM'+g_arrCNL[x]+i),arrMarketInfo[i],cn2,'_',0);
							if (i==1) document.getElementById('tdM'+g_arrCNL[x]+'02').className=cn2; // update cell %
						}
					}	
				}// end if
			}//end for
		}// end if
	}// end for
    /************ /UPDATE INDEX (HO/HA/UP) **********/
    	
    /************ UPDATE 3 HO SESSION **********/
	for (var i=1; i<=3; i++)
		if (SERVER_DATA.indexOf('(S'+i+'')!=-1)
		{
			var SESSION_INFO;
			SESSION_INFO=Right(SERVER_DATA,SERVER_DATA.length-SERVER_DATA.indexOf('(S'+i+'')-4);
			SESSION_INFO=Left(SESSION_INFO,SESSION_INFO.indexOf(')'));
			////////console.info('SESSION_INFO_%s=%s', i, SESSION_INFO);	
			
			var arrSessionInfo = new Array(); 
			arrSessionInfo = SESSION_INFO.split('|');
			for (var j=1; j<arrSessionInfo.length; j++)
			{
				////////console.info('i=%s, j=%s, arrSessionInfo[j]=%s', i, j, arrSessionInfo[j]);	
				
				if (arrSessionInfo[j]!='' )
				{
					var c='n', cn2='';
					var img=document.getElementById('imgMHOD'+arrSessionInfo[0]);//imgMHOD3
					
					if(parseFloat(arrSessionInfo[2])==0)	{c='n';};
					if(parseFloat(arrSessionInfo[2])>0)	{c='u';};
					if(parseFloat(arrSessionInfo[2])<0)	{c='d';};
					//////////console.info('c2=%s',c);
					SetImage(img,c);
					///*
					cn2='mar'+c;
					
					//14,15,16+24,25,26+34,35,36 => 'mar' => do not change color of qtty, value, trade no
					if (j==4||j==5||j==6) cn2='mar';
					
					if (document.getElementById('tdMHOD'+arrSessionInfo[0]+(j)))
					{
					    if (document.getElementById('tdMHOD'+arrSessionInfo[0]+(j)).innerHTML!=arrSessionInfo[j])
						    UpdateCell(document.getElementById('tdMHOD'+arrSessionInfo[0]+(j)),arrSessionInfo[j],cn2,'_',0);
					}
					
					if (document.getElementById('tdMHOD'+arrSessionInfo[0]+'02'))
					{
					    if (arrSessionInfo[j]!=''&&j==2)
					    {
						    //////////console.info('document.getElementById(\'tdMHN02\').className=%s; cn2=%s',document.getElementById('tdMHN02').className,cn2);
						    if (document.getElementById('tdMHOD'+arrSessionInfo[0]+'02').className!=cn2)
							    document.getElementById('tdMHOD'+arrSessionInfo[0]+'02').className=cn2;
					    }
					}					
					//*/
				}//end if
			}// end for			
		}//end if
    /************ /UPDATE 3 HO SESSION **********/
	
	/************ UPDATE STOCK (HO/HA/UP) **********/
	if (SERVER_DATA.indexOf('{')!=-1)
	{
	    // array CodeInfo
	    var arrCodeInfo = new Array();	
	    arrCodeInfo = SERVER_DATA.split('}');
	    //Khai bao
	    var cell='';		// cell object
	    var css='';
	    var t='';
	    var v='';
    	
	    for (var i=0; i<arrCodeInfo.length-1; i++)
	    {
		    // Info of each Code
		    var arrInfo = new Array();
		    // get atom info		
		    arrInfo = Right(arrCodeInfo[i],arrCodeInfo[i].length-arrCodeInfo[i].indexOf('{')-1).split('|');
		    // change image (text - arial font)
		    if(document.getElementById('img'+arrInfo[0]) && true)
		    {
			    var img=document.getElementById('img'+arrInfo[0]);
    			
			    // set image state (unicode char + class name)
			    SetImage(img,arrInfo[1]);
    			
			    // get row object
			    var row = document.getElementById('tr'+arrInfo[0]);
    			
			    for (var j=2; j<arrInfo.length; j++)
			    {
				    cell = row.cells[j+2];
				    css='';
				    t='';
				    v='';

				    if (arrInfo[j]!='') //truong hop co gia tri
				    {
					    if(isNaN(Left(arrInfo[j],1)) == false)//neu la so
					    {
						    v = arrInfo[j];
					    }
					    else if (isNaN(Left(arrInfo[j],1)) == true)//neu la chu
					    {
						    // truong hop chi update class => gia thay doi tu gia san sang gia TC + KL ko doi => doi mau gia + mau KL tu xanh => vang
						    if (arrInfo[j].length==1) 
							    v='z';
						    else 
							    v = arrInfo[j].substring(1);
						    css	 = GetCSS(Left(arrInfo[j],1));
					    }
				    }
				    if (j>=2&&j<=7) t='b';      // khu vuc background black : 3 gia mua tot nhat
				    if (j>=8&&j<=10) t='g';     // khu vuc background grey  : gia khop, kl khop , +/-
				    if (j>=11&&j<=16) t='b';    // khu vuc background black : 3 gia ban tot nhat
				    if (j>=17&&j<=23) t='g';    // khu vuc background grey  : tong KL, open, high, low, FB, FS
				    //////////console.info('cell=%s,v=%s,css=%s,t=%s',cell,v,css,t);
				    // update cell
				    UpdateCell(cell,v,css,t);
			    } // end for j			
		    }// end if , exist image ?			
	    }// end for i
	}// end if (SERVER_DATA.indexOf('{')!=-1)	
	/************ /UPDATE STOCK (HO/HA/UP) **********/
}

function Left(str, n){if (n <= 0)return "";	else if (n > String(str).length) return str; else return String(str).substring(0,n);}
function Right(str, n){if (n <= 0) return ""; else if (n > String(str).length) return str; else {var iLen = String(str).length;return String(str).substring(iLen, iLen - n);}}


// 1. update value + color
// 2. update color
// o-object (cell)
// v-value (price, qtty) if v=='0' => need clear cell value
// c-class name
// t-'b'-black background; 'g'-grey background
function UpdateCell(o,v,c,t)
{	
	if (!o)return false;
	if (v==''&&c=='')return false;
	if ((v=='ATO'||v=='ATC')&&o.innerHTML==v)return false;
	if ((v=='0')&&o.innerHTML=='')return false;
	if (v=='0' && Left(c,3)!='mar') v=''; // '0' market index can not hide; stock can hide
	var bg=(t=='g'?g_cbg:g_cbb);
	o.style.backgroundColor=g_cbh;
	//console.info('g_cbh2=%s          bg=%s',g_cbh2,bg);
	setTimeout(function(){if(v!='z')o.innerHTML=v;if(c!='')o.className=c;o.style.backgroundColor=g_cbh2;
		setTimeout(function(){o.style.backgroundColor=bg}, g_thau)
		}, g_thbu);
}

/*
i - image object
s - state : U-up D-down N-nochange
text (Unicode-Arial font)
class name (include color: green, red, yellow)
*/
function SetImage(i,s)
{
	if (!i) return false;	
	if (s.toUpperCase()=='U') if (i.innerHTML!=g_tiu) {i.innerHTML=g_tiu; i.className='iu';}
	if (s.toUpperCase()=='D') if (i.innerHTML!=g_tid) {i.innerHTML=g_tid; i.className='id';}
	if (s.toUpperCase()=='N') if (i.innerHTML!=g_tin) {i.innerHTML=g_tin; i.className='in';}
}

// dinh nghia trong Library/4g.css
// server ma hoa "brc" thanh "a" de tiet kiem bang thong
// client nhan "a" giai ma thanh className "brc" de gan vao cell
function GetCSS(c)
{
	// ceiling {color: #ff00ff}
	if (c=="a") return  "brc";
	if (c=="b") return  "b_c";
	if (c=="c") return  "grc";
	if (c=="d") return  "g_c";

	// floor {color: #66ccff}
	if (c=="e") return  "brf";
	if (c=="f") return  "b_f";
	if (c=="g") return  "grf";
	if (c=="h") return  "g_f";

	// ref {color: #f7ff31}
	if (c=="i") return  "brr";
	if (c=="j") return  "b_r";
	if (c=="k") return  "grr";
	if (c=="l") return  "g_r";

	// up {color: #00ff00}
	if (c=="m") return  "bru";
	if (c=="n") return  "b_u";
	if (c=="o") return  "gru";
	if (c=="p") return  "g_u";

	// down {color: #ff0000}
	if (c=="q") return  "brd";
	if (c=="r") return  "b_d";
	if (c=="s") return  "grd";
	if (c=="t") return  "g_d";

	// ATO/ATC {color: #ffffff}
	if (c=="u") return  "bra";
	if (c=="v") return  "b_a";
	if (c=="w") return  "gra";
	if (c=="x") return  "g_a";

	// undefined {color: #ffffff}
	if (c=="y") return  "br_";
	if (c=="z") return  "b__";
	if (c=="A") return  "gr_";
	if (c=="B") return  "g__";
}


// lay ra text market status
function GetMarketStatus(CenterName, ControlCode, Language)
{
    //console.info('CenterName=%s, ControlCode=%s, Language=%s',CenterName, ControlCode, Language);
    var ms=''
    for (var i=0; i<g_arrMS.length; i++)
    {
        //["HO", "P", "VN", "Khớp lệnh định kỳ mở cửa"]
        var arr=g_arrMS[i];
        if (arr[0]==CenterName && arr[1]==ControlCode && arr[2]==Language)
		{
            ms= arr[3];
			break;
		}	
    }
	
	return ms;
}


// if shown => hide
// if hided => show
function ShowHideMarketHODetail()
{
	if(!document.getElementById('FPTSMarketHODetail')) return false;
	var tbl=document.getElementById('FPTSMarketHODetail');
	if (tbl.style.display=='') tbl.style.display='none'; else tbl.style.display='';
}

// click vao span, check vao checkbox
function cc(CheckBoxID,Code,Span)
{
	var chk;
	if (document.getElementById(CheckBoxID)) 
		chk=document.getElementById(CheckBoxID);
	else
		return false;
		
	chk.checked=!chk.checked;
	
	if(chk.checked) 
		Span.style.color=g_ccc;
	else
		Span.style.color=g_ccu;
}