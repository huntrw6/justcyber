function setCookieMinutes(cname, cvalue, minutes) {
  var d = new Date();
  d.setTime(d.getTime() + (minutes*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}


function isFirefox()
{
	return navigator.userAgent.indexOf('Firefox') > -1;
}

function touchstart_from_click(a)
{
	a.ontouchstart = function(e) { e.preventDefault(); eval(this.getAttribute('onclick')); };
	return;
}

window.onerror = function(message, source, lineno, colno, error)
{
	//return false;
	message = encodeURIComponent(message);
	source = encodeURIComponent(source);
	var gdzie = encodeURIComponent('(' + lineno + ',' + colno + ')');
	var url = location.href; if (window.frameElement) url += ', ' + parent.window.location.href;
	url = encodeURIComponent(url);
	var agent = encodeURIComponent(navigator.userAgent);
	var src = wizboardpath + 'jserror.php?';
	src += 'message='+message + '&source='+source + '&gdzie='+gdzie + '&url='+url + '&agent='+agent;
	src += '&line='+lineno + '&stack='+encodeURIComponent(error.stack);
	(new Image).src = src;
	return false;
}

function openFullscreen(elem) {
	elem = elem || document.documentElement;
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.mozRequestFullScreen) { /* Firefox */
    elem.mozRequestFullScreen();
  } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE/Edge */
    elem.msRequestFullscreen();
  }
}
function closeFullscreen() {
  if (document.exitFullscreen) {
    document.exitFullscreen();
  } else if (document.mozCancelFullScreen) { /* Firefox */
    document.mozCancelFullScreen();
  } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
    document.webkitExitFullscreen();
  } else if (document.msExitFullscreen) { /* IE/Edge */
    document.msExitFullscreen();
  }
}

/*
function toggleFullScreen() // did not work on Safari
{
	var doc = window.document;
	var docEl = doc.documentElement;
	var requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
	var cancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;
	if(!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement)
	{requestFullScreen.call(docEl);}
	else {cancelFullScreen.call(doc);}
}
*/
function toggleFullScreen()
{
	var doc = window.document;
	if(!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement)
		openFullscreen(); else closeFullscreen();
}
function isNotFullScreen()
{
	var doc = window.document;
	return (!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement);
}

function applynoselect(element)
{
 var aaa = document.getElementById('noselectstyle');
 if (!aaa)
 {
  var style = document.createElement('style');
  style.id = 'noselectstyle';
  var text = '.noselect { -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; -webkit-tap-highlight-color: transparent; }';
  style.appendChild(document.createTextNode(text));
  document.head.appendChild(style);
 } 
 var all = element.querySelectorAll('*'); //var all = element.getElementsByTagName("*");
 for (var i=0; i<all.length; i++)
 {
  all[i].style.userSelect="none";
  all[i].classList.add("noselect");
 }
}

function select_randomly_N_elements_from_Array(N,arr)
{
	var len = arr.length;
	if (len == 0) return [];
	if (N > len) N = len;
	var ret = [];
	var ilejuz = 0;
	var juzwybrane = '';
	while (ilejuz < N)
	{
		var index = Math.floor((Math.random() * len));
		if (juzwybrane.indexOf('(' + index + ')') == -1)
		{
			juzwybrane += '(' + index + ')';
			ret.push(arr[index]);
			ilejuz++;
		}
	}
	return ret;
}
var g_stateline = [];

function g_stateline_init(FEN)
{
	g_stateline = [ { FEN: FEN, halfmove: 0, message: '' } ];
}

function g_stateline_push(move,ruch,terazfen,whose,message)
{
	g_stateline.push( { garbomove: (ruch != '--') ? move : null, SAN: ruch, FEN: terazfen, whose: whose, message: message } );
	g_stateline[0].halfmove++;
}

function g_stateline_pop()
{
	if (g_stateline.length > 1)
	{
		g_stateline[0].halfmove--;
		return g_stateline.pop();
	}
	return null;
}

function g_stateline_absolute_result()
{
	function kinglessresult()
	{
		if (g_stateline_ismated()) return 1;
		if (g_stateline_isStalemated()) return 1.5;
		if (last > 0 && g_stateline[last].SAN.indexOf('=') > -1) return 1.5;
		if (isdrawbyrepetition()) return 3;
		//if (InsufficientMaterial(GetFen())) return 4;
		if (isdrawby50()) return 5;
		return 0;		
	}
	var n = g_stateline[0].halfmove; var last = g_stateline.length-1;
	if (n < last) return 0;

	var startfen = g_stateline[0].FEN; var nowfen = g_stateline[n].FEN;
	var metokens = (g_toMove && startfen.indexOf('T')>-1) || (g_toMove == 0 && startfen.indexOf('t')>-1);
	var menotokens = (g_toMove && nowfen.indexOf('T')==-1) || (g_toMove == 0 && nowfen.indexOf('t')==-1);
	if (metokens && menotokens) return 1.5;

	var mekingless = (g_toMove && g_noWhiteKing) || (g_toMove == 0 && g_noBlackKing);
	if (mekingless) return kinglessresult();

	if (g_stateline_ismated()) return 1;
	if (g_stateline_isStalemated()) return 2;
	if (isdrawbyrepetition()) return 3;
	if (InsufficientMaterial(GetFen())) return 4;
	if (isdrawby50()) return 5;
		
	return 0;
}
/*
function g_stateline_result()
{
	var r = g_stateline_absolute_result();
	var noresult = ((parseInt(mode) & 128) == 128) || successrule == 'destroy';
	if (noresult) if (r != 1) r = 0; // no-result mode respects only checkmate
	return r;
}
*/
function czy_noresult_mode()
{
 if (isEditor()) return el('noresultbox').checked;
 if (successrule === 'destroy') return true;
 return (parseInt(mode) & 128) === 128;
}
function g_stateline_result()
{
        const r = g_stateline_absolute_result();
        if (czy_noresult_mode()) if (r !== 1) return 0; // no-result mode respects only checkmate
        return r;
}

function g_stateline_resign()
{
	g_stateline_clear();
	changeFEN(g_stateline[0].FEN);
	g_stateline_update();
}

function g_stateline_goto(n)
{
	var last = g_stateline.length-1;
	if (g_enginethinking || n < 0 || n > last) return false;

	var message = el('movelistmessage'); if (message) message.style.opacity = (n == last) ? '1' : '0.5';
	var alertm = el('alert'); if (alertm) alertm.style.visibility = (n == last) ? 'visible' : 'hidden';
	//var resultpanel = el('ResultPanel'); if (resultpanel) resultpanel.style.visibility = (n == last) ? 'visible' : 'hidden';

	var nowyfen = g_stateline[n].FEN;
	var result = InitializeFromFen( nowyfen );
	if (result.length != 0) { console.log('Error. Cannot initialize from FEN '+nowyfen+'\n\n'+result); }
	EnsureAnalysisStopped();
	InitializeBackgroundEngine();
	g_playerWhite = !!g_toMove;
	g_backgroundEngine.postMessage("position " + GetFen());
	if (flipcyfra == '0' && nowyfen.indexOf(" b ") > -1) g_playerWhite = !g_playerWhite;
	if (flipcyfra == '1' && nowyfen.indexOf(" w ") > -1) g_playerWhite = !g_playerWhite;
	RedrawBoard(); updateczyjruch();
	if (last>0) for (var i = g_stateline.length-1; i >= 1; i--) el('halfmove'+i).style.opacity = (i <= n) ? '1' : '0.5';
	el('FenTextBox').value = nowyfen;
	var a = el('playcomputer'); if (a) { a.href = playthisURL(); a.target = '_blank'; a.title = 'link to play this position against computer (in new window)'; }
	g_stateline[0].halfmove = n;
	GameOver = g_stateline_result();
	if (g_selectedPiece) g_selectedPiece.style.background = 'none';
	g_selectedPiece = null;
	g_stateline_decorate();
	g_stateline_update();
	return true;
}


function g_stateline_update()
{
	g_stateline_update_movelistbanner();
	g_stateline_updatebuttons();
	g_stateline_updatemoves_scroll();
	g_stateline_update_resultpanel();
}

function g_stateline_refresh()
{
	el('boardcontainer').style.background = (g_puzzle_czyselfplay()) ? '#ddeedd' : 'white';
	if (el('selfplayexamtip')) el('selfplayexamtip').style.display = (g_puzzle_czyselfplay()) ? 'block' : 'none';
	if (g_enginethinking)
	{
		RedrawBoard();
		updateczyjruch();
		g_stateline_decorate();
		g_stateline_update();
		return;
	}
	g_stateline_goto(g_stateline[0].halfmove);
}

function g_stateline_clear()
{
	try { cancelselected(); } catch(err) { };
	try { clearalert(); } catch(err) { };
	var resultpanel = el('ResultPanel'); if (resultpanel) resultpanel.parentElement.removeChild(resultpanel);
	var message = el('movelistmessage'); if (message) message.parentElement.removeChild(message);
	GameOver = 0;
	el('movelist').style.fontWeight = 'normal'; // addcurrentline makes movelist bold
	userthinkingstyle();
}

function g_stateline_droptail()
{
	var n = g_stateline[0].halfmove; var last = g_stateline.length-1;
	if (n == last) return;
	for (var i = last; i > n; i--)
	{
		updatemoves_pop();
		FENarray.pop();	
		var a = el('PgnTextBox').value.split(' '); a.pop(); a.pop(); el('PgnTextBox').value = a.join(' ')+' ';
		var dropped = g_stateline_pop();
		if (dropped.SAN != '--') g_allMoves.pop();
	}
	g_stateline[0].halfmove = n;
	g_stateline_clear();
	g_stateline_update();
}

function g_stateline_halfmoveback()
{
	var last = g_stateline.length-1;
	if (last == 0) return
	g_stateline_goto(last-1);
	g_stateline_droptail();
	var last = g_stateline.length-1;
	if (el('movelist').innerHTML.indexOf('?')>-1) keepwrongmovealert();
}

function g_stateline_ismated() { return g_inCheck && GenerateValidMoves().length == 0; }
function g_stateline_isStalemated() { return !g_inCheck && GenerateValidMoves().length == 0; }
function g_stateline_isWhiteToMove() { return g_stateline[g_stateline[0].halfmove].FEN.indexOf(' w ') > -1; }
function g_stateline_isBlackToMove() { return !g_stateline_isWhiteToMove(); }
function g_stateline_isWhiteChecked() { return g_inCheck && g_stateline_isWhiteToMove(); }
function g_stateline_isBlackChecked() { return g_inCheck && g_stateline_isBlackToMove(); }

function g_stateline_checkarrow()
{
	if (!g_inCheck) return;
	var n = g_stateline[0].halfmove;
	if (n == 0) return;
	var move = g_stateline[n].garbomove;
	UnmakeMove(move); MakeMove(move);
	g_kingchecked = g_kingtake;
	checkarrow();
}

function g_stateline_showghost()
{
	var n = g_stateline[0].halfmove;
	var SAN = g_stateline[n].SAN;
	var move = g_stateline[n].garbomove;
	if (n == 0 ||  SAN == '--' || SAN.indexOf('x')==-1 || (move & moveflagEPC)) return;
	var starapoza = pozafromFENtokens(g_stateline[n-1].FEN);
	var nowapoza = pozafromFENtokens(g_stateline[n].FEN);
	var x = ((move >> 8) & 0xF) - 4, y = ((move >> 12) & 0xF) - 2; var x2 = x+1, y2 = 8-y;
	var ghostwhat = starapoza[squareIndex(x2,y2,8,8,false)];
	var realwhat = nowapoza[squareIndex(x2,y2,8,8,false)];
	var ghost = svgmerida(ghostwhat);
	var XxY = realwhat.toUpperCase() + 'x' + ghostwhat.toUpperCase();
	if (XxY == 'NxN') ghost.style.transform = (!czyromantic()) ? 'scaleX(-1)' : 'scaleX(1)';
	else if (XxY == 'RxR') ghost.style.transform = 'rotate(90deg)';
	else if (XxY == 'RxB') ghost.style.transform = 'rotate(90deg)';
	else if (XxY == 'QxP') ghost.style.transform = 'rotate(90deg)';
	else if (XxY == 'BxP') ghost.style.transform = 'rotate(90deg)';
	else if (XxY == 'QxB') ghost.style.transform = 'rotate(90deg)';
	else if (XxY == 'RxP') ghost.style.transform = 'rotate(90deg)';
	else if (XxY == 'KxB') ghost.style.transform = 'rotate(180deg)';
	else if (ghostwhat.toLowerCase() == realwhat.toLowerCase()) ghost.style.transform = 'rotate(180deg)';
	var div = newel('div'); div.appendChild(ghost);
	ghost.style.opacity = '0.2';
	var svgcode = div.innerHTML;
	if (!g_playerWhite) { y = 7 - y; x = 7 - x; }
	var toSquare = g_uiBoard[y * 8 + x];
	toSquare.style.backgroundImage = "url('data:image/svg+xml;base64,"+window.btoa(svgcode)+"')";
	toSquare.style.backgroundSize = 'cover';	
}

function color_theking_in_diagram(container, king, reason)
{
 console.assert(king === 'K' || king === 'k');
 console.assert(reason==='+' || reason==='#' || reason==='$');
 const kingsvg = container.querySelector('svg.'+king);
 if (kingsvg)
 {
  let html = kingsvg.outerHTML;
  if (king === 'K' && reason !== '$') // white king checked
  {
   html = html.replace(/#fff/g,'rgb(255,230,230)');
   html = html.replace(/#000/g,'rgb(150,0,0)');
  }
  if (king === 'K' && reason === '$') // white king stalemated
  {
   html = html.replace(/#fff/g,'#ffa');
  }
  if (king === 'k' && reason !== '$') // black king checked
  {
   html = html.replace(/#fff/g,'red');
  }
  if (king === 'k' && reason === '$') // black king stalemated
  {
   html = html.replace(/#fff/g,'yellow');
  }
  const div = newel('div');
  div.innerHTML = html;
  const newking = div.firstChild;
  if (reason === '#') newking.style.transform = 'rotate(90deg)';
  //if (reason === '#') newking.setAttribute('transform','rotate(90)');
  const parentel = kingsvg.parentElement;
  parentel.removeChild(kingsvg);
  parentel.appendChild(newking);
 }
}



function g_stateline_decorate()
{
	if (g_inCheck)
	{
		g_stateline_checkarrow();
		const king = (g_stateline_isWhiteChecked()) ? 'K' : 'k';
		const reason = (g_stateline_ismated()) ? '#' : '+';
		color_theking_in_diagram(el('chessboardtable'),king,reason);
	}
	if (g_stateline_isStalemated())
	{
		const king = (g_stateline_isWhiteToMove()) ? 'K' : 'k';
		color_theking_in_diagram(el('chessboardtable'),king,'$');
	}
	if (g_stateline_result() >= 3) RecolorBoardSquares('#99ff99','#ddffdd');

	var n = g_stateline[0].halfmove;
	if (0 < n) // indicate last two garbomoves
	{
		var moves = [];
		for (var i=1; i<=n; i++)
		{
			var move = g_stateline[i].garbomove;
			if (move) moves.push(move); // this skips null moves
		}
		var ile = moves.length;
		if (ile == 1) indicatemove(moves[0]); else if (ile >= 2) indicatemove(moves[ile-1],moves[ile-2]);
	}
	
	//2022-07-02
	if (n == 0) indicate_prefen_move();
	
	g_stateline_showghost();
}

function g_stateline_firstwrong()
{
	var n = g_stateline[0].halfmove;
	if (n == 0) return;
	if (n >= 2) for (var i=n-1; i>=1; i--) if (el('halfmove'+i).innerHTML.indexOf('?') > -1) return;
	//g_stateline[n].SAN += '?';
	el('halfmove'+n).appendChild(document.createTextNode("?"));
}

function g_stateline_resetFEN(FEN) // used in editor
{
	FENarray = [FEN];
	g_stateline_clear();
	el('movelist').innerHTML = '';
	g_stateline_init(FEN);
	g_stateline_refresh();
}

function replace(a,m,r) { var s = a.replace(m,r); if (s == a) return s; return replace(s,m,r); }

var fen,flipcyfra;

function set_fen_flipcyfra_from_transferfen(transferfen)
{
 fen = '?fen='+transferfen;
 flipcyfra = '0';
 if (fen != "")
 {
  if (fen.charAt(5)=='1') flipcyfra = '1';

  if (fen.length>6) fen = fen.substring(6,fen.length); else fen = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";
 }
 else fen = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";

 fen = replace(fen,"_"," ");
 fen = replace(fen,"X","/");

 var fenparts = fen.split(' ');
 fen = fenparts[0] + ' ' + fenparts[1] + ' ' + fenparts[2] + ' ' + fenparts[3] + ' ' + fenparts[4] + ' ' + fenparts[5];
}

var GameOver = false;
var FENarray = new Array();

var zadanie = false; // the puzzle is initiated via the global zadanie

function onUserMove(move)
{
 vibrate('onUserMove');
 //indicatemove(move);
 var wariant = document.getElementById('PgnTextBox').value;
 if (zadanie) czlowiekzagral(zadanie,wariant);
         else setTimeout("SearchAndRedraw()", 0);
}

function onComputerMove(move)
{
	userthinkingstyle();
 //aftermove(move);
 if (successrule == 'draw' && isdraw()) declaresuccess();
 //document.title = getscore();
}

function refreshFEN()
{
 FENarray[0] = g_fens[0];
 document.getElementById('FenTextBox').value = FENarray[FENarray.length-1];
 var a = document.getElementById('playcomputer');
 if (a)
 {
  a.href = playthisURL();
  a.target = '_blank';
  a.title = 'link to play this position against computer (in new window)';
 }
}

function g_stateline_makemove(move,whose)
{
	g_stateline_droptail();
	if (move)
	{
		UpdatePgnTextBox(move);
		//if (pv) UpdatePVDisplay(pv); else if (InitializeBackgroundEngine()) { g_backgroundEngine.postMessage(FormatMove(move)); }
		if (whose != 'engine') if (InitializeBackgroundEngine()) { g_backgroundEngine.postMessage(FormatMove(move)); }
		g_allMoves[g_allMoves.length] = move;
		MakeMove(move);
		UpdateFromMove(move);
	}
	else
	{
		makenullmove();
		el("PgnTextBox").value += "--" + " ";
	}
 cancelselected();
 updatemoves_push();

 FENarray[0] = g_fens[0];
 var terazfen = GetFen();
 var whitestarted = FENarray[0].indexOf('w') > -1;
 var n = FENarray.length + 1;
 var fullmove = whitestarted ? Math.floor((n+1)/2) : Math.floor(n/2)+1 ;
 fullmove += Number( g_fens[0].split(' ')[5] ) - 1;

 var halfmove = Number( FENarray[FENarray.length-1].split(' ')[4] ) + 1;
 var ruchy = document.getElementById('PgnTextBox').value + 'last';
 ruchy = ruchy.split(' ');
 var ruch = ruchy[ruchy.length-2];
 if (ruch.indexOf('x')>-1 || ruch.indexOf('=')>-1) halfmove = 0;
 if (ruch.indexOf('K')==-1 && ruch.indexOf('Q')==-1 && ruch.indexOf('R')==-1
  && ruch.indexOf('B')==-1 && ruch.indexOf('N')==-1) halfmove = 0;  

 terazfen += ' ' + halfmove + ' ' + fullmove;
 FENarray.push(terazfen);
 refreshFEN();

 g_ids_safe = zadanie[6];

 whose = whose || 'computer'; var message = '';
 if (isEditor()) message = (whose == 'engine') ? prognosis_htmlstring(el('output').innerHTML) : '';
 g_stateline_push(move,ruch,terazfen,whose,message);
 g_stateline_decorate();
 GameOver = g_stateline_result();
 if (GameOver)
 {
  var color = 'black';
  if (GameOver >= 1 && GameOver < 2) color = (whose == 'user') ? 'green' : 'red';
  var gameover = '<span class=kolor'+color+'>'+g_stateline_gameover_htmlstring()+'</span>';
  g_stateline[g_stateline.length-1].message = gameover;
 }
 g_stateline_update();
 g_stateline_updatemoves_gameover();
}

function prognosis_htmlstring(pv)
{
	var x = parseInt(pv.split(' ')[1].substr(6));
	var winning = x >= maxMateBuffer;
	var losing = x <= minMateBuffer;
	if (!winning && !losing) return '';
	//var moves = ponumerujarray(sameruchyarray(pv),g_toMove,'');
	var fullmovenumber = parseInt(g_stateline[g_stateline[0].halfmove].FEN.split(' ')[5]);
	var moves = ponumerujpgn(sameruchyarray(pv),fullmovenumber,g_toMove);
	console.log(pv); console.log(moves);
	var ply = (winning) ? (maxEval - x) : (x - minEval);
	var n = (winning) ? (ply+1)/2 : (ply/2);
	var message = '';
	if (winning) message = (g_toMove == 0) ? WhiteWinsInMessage(n) : BlackWinsInMessage(n);
			else message = (g_toMove == 0) ? WhiteLosesInMessage(n) : BlackLosesInMessage(n);
	var ret = message + ': ' + moves;
	var color = winning ? 'red' : 'green';
	return '<span class=kolor'+color+'>'+ret+'</span>';
}

function g_stateline_gameover_htmlstring()
{
	var GameOver = g_stateline_result();
	if (GameOver == 0) return '';
	if (GameOver == 1) return (g_toMove) ? BlackWinsMessage() : WhiteWinsMessage();
	if (GameOver == 2) return StaleMateMessage();
	if (GameOver == 3) return RepetitionMessage();
	if (GameOver == 4) return NoPowerMessage();
	if (GameOver == 5) return FiftyMessage();
	if (GameOver == 1.5) return (g_toMove) ? WhiteZeroedMessage() : BlackZeroedMessage();
	console.error('unrecognized GameOver code');
}

function g_stateline_updatemoves_gameover()
{
	if ((parseInt(mode) & 128) == 128) return; // no result setting
	var message = g_stateline_gameover_htmlstring();
	if (message)
	{
		var span = newel('span'); span.id = "movelistmessage";
		span.innerHTML = ' '+message;
		el('movelist').appendChild(span);
	}
}

function ismate() { return (document.getElementById('PgnTextBox').value.indexOf('#') > -1); }

/*
function opraw(message)
{
	return '<span id="movelistmessage"> ' + message + '</span>';
}
*/

/*
function checkmate()
{
 if (!ismate()) return false;
 GameOver = 1; //true;
 var message = '';
 var movelist = document.getElementById('movelist');
 var whitetomove = FENarray[FENarray.length-1].indexOf('w') > -1;
 if (whitetomove)
 {
  message += BlackWinsMessage();
  movelist.innerHTML += opraw(message); 
  if (GameOverAlert) alert('BLACK WINS BY CHECKMATE');
  return true;
 }
 else
 {
  message += WhiteWinsMessage();
  movelist.innerHTML += opraw(message); 
  if (GameOverAlert) alert('WHITE WINS BY CHECKMATE');
  return true;
 }
}
*/



function ___pozafromFENtokens(fen)
{
 if (!fen) return "";
 var width = 8, height = 8;
 var fenek = fen.split(' ');
 var horizontals = fenek[0].split('/');
 if (horizontals.length != height) return "";
 var pozycja = "";
 for (var y=height-1; y>=0; y--)
 {
  var hora = horizontals[y];
  while (hora != "")
  {
   switch(hora.charAt(0))
   {
    case 'K' : pozycja += "K"; break;
    case 'k' : pozycja += "k"; break;
    case 'Q' : pozycja += 'Q'; break;
    case 'q' : pozycja += 'q'; break;
    case 'R' : pozycja += 'R'; break;
    case 'r' : pozycja += 'r'; break;
    case 'B' : pozycja += 'B'; break;
    case 'b' : pozycja += 'b'; break;
    case 'N' : pozycja += 'N'; break;
    case 'n' : pozycja += 'n'; break;
    case 'P' : pozycja += 'P'; break;
    case 'p' : pozycja += 'p'; break;
    case 'T' : pozycja += 'T'; break;
    case 't' : pozycja += 't'; break;
    case '1' : pozycja += '_'; break;
    case '2' : pozycja += "__"; break;
    case '3' : pozycja += '___'; break;
    case '4' : pozycja += '____'; break;
    case '5' : pozycja += '_____'; break;
    case '6' : pozycja += '______'; break;
    case '7' : pozycja += '_______'; break;
    case '8' : pozycja += '________'; break;
    default  : return "";
   }
   hora = hora.substring(1,hora.length);
  }
  pozycja += '_';
 }
 return pozycja;
}
function same_color_bishops_only(fen)
{
 let poza = ___pozafromFENtokens(fen).toLowerCase();
 let dark = ''; let light = '';
 for (let i=0; i < poza.length; i++) if (i % 2) dark += poza[i]; else light += poza[i];
 dark = dark.replace(/k/g, ''); light = light.replace(/k/g, ''); // remove both kings
 dark = dark.replace(/_/g, ''); light = light.replace(/_/g, '');
 dark = new Set(dark); light = new Set(light);
 dark = Array.from(dark).join(''); light = Array.from(light).join('');
 if (dark === 'b' && light === '') return true;
 if (light === 'b' && dark === '') return true;
 return false; 
}


function InsufficientMaterial(fen)
{
 var aa = fen.toLowerCase();
 if (aa.indexOf('t')>-1) return false;
 if (aa.indexOf('p')>-1 || aa.indexOf('q')>-1 || aa.indexOf('r')>-1) return false;
 
 var i, p = fen.split(' ')[0];
 var B=0, N=0, b=0, n=0;
 for (i=0; i<p.length; i++)
 {
  aa = p.charAt(i);
  if (aa == 'B') B++;
  if (aa == 'N') N++;
  if (aa == 'b') b++;
  if (aa == 'n') n++; 
 }
 if (B+N+b+n <= 1) return true;

 if (same_color_bishops_only(fen)) return true;

 return false;
}

function shortfen(a) { var b = a.split(' '); return b[0] + b[1] + b[2] + b[3]; }

function isdrawbyrepetition()
{
 var ile = FENarray.length;
 if (ile < 5) return false;
 var r = 0;
 var obecna = FENarray[ile-1];
 for (var i = ile-2; i >= 0 ; i--)
  if (shortfen(FENarray[i]) == shortfen(obecna)) r++;
 return r >= 2;
}

function isstalemate() { return (document.getElementById('PgnTextBox').value.indexOf('$')>-1); }

function isWhiteToMove()
{
 var ile = FENarray.length;
 var obecna = FENarray[ile-1];
 return obecna.indexOf(' w ')>-1;
}

function isWhiteStalemated() { return isstalemate() && isWhiteToMove(); }
function isBlackStalemated() { return isstalemate() && !isWhiteToMove(); }
function isWhiteChecked() { return GetFen().indexOf(' w ')>-1 && g_inCheck; }
function isBlackChecked() { return GetFen().indexOf(' b ')>-1 && g_inCheck; }

function isdrawby50()
{
 var ile = FENarray.length;
 return ( Number( FENarray[ile-1].split(' ')[4] ) >= 100 )
}

function isdraw() { return isstalemate() || InsufficientMaterial(GetFen()) || isdrawbyrepetition() || isdrawby50(); }

/*
function claimdraw()
{
 var movelist = document.getElementById('movelist');

 if (isstalemate())
 {
  GameOver = 2; //true;
  movelist.innerHTML += opraw(StaleMateMessage());
  if (GameOverAlert) alert('DRAW BY STALEMATE');
  //RedrawPieces();
  return;
 }

 if (InsufficientMaterial(GetFen()))
 {
  GameOver = 4; //true;
  movelist.innerHTML += opraw(NoPowerMessage());
  if (GameOverAlert) alert('DRAW BY INSUFFICIENT MATERIAL');
  //RecolorBoardSquares('#99ff99','#ddffdd');
  return;
 }

 if (isdrawbyrepetition())
 {
  GameOver = 3; //true;
  movelist.innerHTML += opraw(RepetitionMessage());
  if (GameOverAlert) alert('DRAW BY REPETITION');
  //RecolorBoardSquares('#99ff99','#ddffdd');
  return;
 }
 
 if (isdrawby50())
 {
  GameOver = 5; //true;
  movelist.innerHTML += opraw(FiftyMessage());
  if (GameOverAlert) alert('DRAW BY FIFTY MOVES');
  //RecolorBoardSquares('#99ff99','#ddffdd');
  return;
 }
}
*/

function changeFEN(nowyfen)
{
 var result = InitializeFromFen( nowyfen );
 if (result.length != 0)
 {
  alert('Error. Cannot initialize from FEN '+nowyfen+'\n\n'+result);
  return;
 }
 g_allMoves = [];
 EnsureAnalysisStopped();
 InitializeBackgroundEngine();
 g_playerWhite = !!g_toMove;
 g_backgroundEngine.postMessage("position " + GetFen());
 if (flipcyfra == '0' && nowyfen.indexOf(" b ") > -1) g_playerWhite = !g_playerWhite;
 if (flipcyfra == '1' && nowyfen.indexOf(" w ") > -1) g_playerWhite = !g_playerWhite;
 if (flipcyfra == '0') a1h8(); else h8a1();
 updateczyjruch();
 //RedrawPieces();
 g_fens[0] = nowyfen;
 GameOver = false;
 FENarray = new Array();
 refreshFEN();
 document.getElementById('PgnTextBox').value = '';
 RedrawBoard(); //RedrawPieces(); - to remove ghosts
 updatemoves_clear();
 g_stateline_init(nowyfen);
 g_stateline_decorate();
}

function newbutton()
{
 changeFEN(fen);
 return;
}

function literka(i)
{
 if (i==1) return 'a'; if (i==2) return 'b'; if (i==3) return 'c';
 if (i==4) return 'd'; if (i==5) return 'e'; if (i==6) return 'f';
 if (i==7) return 'g'; if (i==8) return 'h'; return '('+i+')';
}

function boardcontainer()
{
 var style = 'cursor:pointer; color:black; font-family:Verdana';
 var fliptoken = '<span id=flipspan title="'+fliptheboardtext()+'" style="'+style+'" onclick="flipclick();" >'+flipicon_svgcode()+'</span>';
 var fliptd = '<input id="flipbox" type="checkbox" style="display:none" >'+fliptoken;
 
 var border = 'none';
 var table = document.createElement('table');
 table.id = 'boardcontainer';
 table.style.color = 'gray';
 table.style.borderCollapse = 'collapse';
 var tr = document.createElement('tr');
 var td = document.createElement('td');
 td.id = 'controlstd';
 td.style.padding = '0';
 td.style.height = '18px';
 td.style.width = '18px';
 td.innerHTML = '&nbsp;';
 tr.appendChild(td);
 for (var i=1; i<=8; i++)
 {
  td = document.createElement('td');
  td.id = literka(i)+8;
  td.style.padding = '0';
  td.style.textAlign = 'center';
  td.style.verticalAlign = 'bottom';
  td.style.width = g_cellSize+'px';//'45px';
  td.innerHTML = literka(i);
  tr.appendChild(td);
 }
 td = document.createElement('td');
 td.id = "topczyjruch";
 td.style.padding = '0';
 td.style.width = '18px';
 td.innerHTML = '&nbsp;';
 tr.appendChild(td); 
 table.appendChild(tr);
 
 tr = document.createElement('tr');
 td = document.createElement('td');
 td.id = 'l8';
 td.style.padding = '0';
 td.style.textAlign = 'right';
 td.style.paddingRight = '2px';
 td.style.height = g_cellSize+'px';//'45px';
 td.innerHTML = '8';
 tr.appendChild(td);
 td = document.createElement('td');
 td.id = 'board';
 td.colSpan = '8';
 td.rowSpan = '8';
 td.style.padding = '0';
 td.style.border = border;
 td.style.width = (8*g_cellSize)+'px';//'360px';
 td.style.height = (8*g_cellSize)+'px';//'360px';
 td.style.verticalAlign = 'top';
 td.innerHTML = '&nbsp;';//'<div id="board"></div>';
 tr.appendChild(td); 
 td = document.createElement('td');
 td.id = 'r8';
 td.style.padding = '0';
 td.style.textAlign = 'left';
 td.style.paddingLeft = '2px';
 td.innerHTML = '8';
 tr.appendChild(td);
 table.appendChild(tr);

 for (var i=7; i>=1; i--)
 {
  tr = document.createElement('tr');
  td = document.createElement('td');
  td.id = 'l'+i;
  td.style.padding = '0';
  td.style.textAlign = 'right';
  td.style.paddingRight = '2px';
  td.style.height = g_cellSize+'px';//'45px';
  td.innerHTML = i;
  tr.appendChild(td);
  td = document.createElement('td');
  td.id = 'r'+i;
  td.style.padding = '0';
  td.style.textAlign = 'left';
  td.style.paddingLeft = '2px';
  td.innerHTML = i;
  tr.appendChild(td);
  table.appendChild(tr);
 }

 tr = document.createElement('tr');
 td = document.createElement('td');
 td.style.padding = '0';
 td.innerHTML = fliptd;
 tr.appendChild(td);
 for (var i=1; i<=8; i++)
 {
  td = document.createElement('td');
  td.id = literka(i)+'1';
  td.style.padding = '0';
  td.style.textAlign = 'center';
  td.style.verticalAlign = 'top';
  td.innerHTML = literka(i);
  tr.appendChild(td);
 }
 td = document.createElement('td');
 td.id = "bottomczyjruch";
 td.style.height = '18px';
 td.innerHTML = '&nbsp;';
 tr.appendChild(td); 
 table.appendChild(tr);

 applynoselect(table); 
 return table;
}

function appendboard(element,size)
{
 if (size == null) size = 396;
 var A = size;
 var minsize = 8*27 + 2*18;
 if (A < minsize) A = minsize;
 element.style.width = A + 'px';
 element.style.height = A + 'px'; 
 g_cellSize = Math.floor((A-2*18)/8); 
 var nadmiar = (A-2*18)%8;
 var marginleft = Math.floor(nadmiar/2);
 var table = boardcontainer();
 table.style.marginLeft = marginleft+'px';
 element.appendChild(table);
 touchstart_from_click(el('flipspan'));
}

function updateczyjruch()
{	
 var bottomczyjruch = document.getElementById('bottomczyjruch');
 var topczyjruch = document.getElementById('topczyjruch');
 bottomczyjruch.innerHTML = '';
 topczyjruch.innerHTML = '';
 bottomczyjruch.style.verticalAlign = 'top';
 topczyjruch.style.verticalAlign = 'bottom';
 if (g_puzzle_czy_allow_engine_help())
 {
  bottomczyjruch.setAttribute('title',clicktoforceenginetomovetext());
     topczyjruch.setAttribute('title',clicktoforceenginetomovetext());
  bottomczyjruch.style.cursor = 'pointer';
     topczyjruch.style.cursor = 'pointer';
  bottomczyjruch.addEventListener('click',user_forcemove);
     topczyjruch.addEventListener('click',user_forcemove); 
  bottomczyjruch.addEventListener('click',function(){ galogg('triangle','bottom'); });
     topczyjruch.addEventListener('click',function(){ galogg('triangle','top'); }); 
 }
 var whitetomove = GetFen().indexOf(' w ') > -1;
 var flip = document.getElementById('flipbox').checked;
 var triangle = (whitetomove) ? ((flip) ? topczyjruch : bottomczyjruch) : ((flip) ? bottomczyjruch : topczyjruch);
 var   empty = (!whitetomove) ? ((flip) ? topczyjruch : bottomczyjruch) : ((flip) ? bottomczyjruch : topczyjruch);
 triangle.appendChild(tomovetriangle(flip,whitetomove));
 empty.appendChild(tomovetriangle(flip,whitetomove));//empty.appendChild(tomovetriangle(flip,whitetomove,'empty'));
 empty.style.transform = 'scale(0.9)';
}

function flipchessboard()
{
 var flipbox = document.getElementById('flipbox');
 flipbox.checked = !flipbox.checked;
 UIChangeStartPlayer();
 a1h8flip();
 updateczyjruch();
 flipcyfra = document.getElementById('flipbox').checked ? '1' : '0';
 transferfen = flipcyfra + transferfen.substr(1);
 refreshFEN();
}

function flipclick()
{
 flipchessboard();
 galogg('flip','-');
 cancelselected();
 g_stateline_decorate();
}

function h8a1()
{
 document.getElementById('a8').innerHTML = 'h'; document.getElementById('b8').innerHTML = 'g'; document.getElementById('c8').innerHTML = 'f';
 document.getElementById('d8').innerHTML = 'e'; document.getElementById('e8').innerHTML = 'd'; document.getElementById('f8').innerHTML = 'c';
 document.getElementById('g8').innerHTML = 'b'; document.getElementById('h8').innerHTML = 'a'; document.getElementById('a1').innerHTML = 'h';
 document.getElementById('b1').innerHTML = 'g'; document.getElementById('c1').innerHTML = 'f'; document.getElementById('d1').innerHTML = 'e';
 document.getElementById('e1').innerHTML = 'd'; document.getElementById('f1').innerHTML = 'c'; document.getElementById('g1').innerHTML = 'b';
 document.getElementById('h1').innerHTML = 'a'; document.getElementById('l1').innerHTML = '8'; document.getElementById('l2').innerHTML = '7';
 document.getElementById('l3').innerHTML = '6'; document.getElementById('l4').innerHTML = '5'; document.getElementById('l5').innerHTML = '4';
 document.getElementById('l6').innerHTML = '3'; document.getElementById('l7').innerHTML = '2'; document.getElementById('l8').innerHTML = '1';
 document.getElementById('r1').innerHTML = '8'; document.getElementById('r2').innerHTML = '7'; document.getElementById('r3').innerHTML = '6';
 document.getElementById('r4').innerHTML = '5'; document.getElementById('r5').innerHTML = '4'; document.getElementById('r6').innerHTML = '3';
 document.getElementById('r7').innerHTML = '2'; document.getElementById('r8').innerHTML = '1';
 document.getElementById('flipbox').checked = true;
}
function a1h8()
{
 document.getElementById('a8').innerHTML = 'a'; document.getElementById('b8').innerHTML = 'b'; document.getElementById('c8').innerHTML = 'c';
 document.getElementById('d8').innerHTML = 'd'; document.getElementById('e8').innerHTML = 'e'; document.getElementById('f8').innerHTML = 'f';
 document.getElementById('g8').innerHTML = 'g'; document.getElementById('h8').innerHTML = 'h'; document.getElementById('a1').innerHTML = 'a';
 document.getElementById('b1').innerHTML = 'b'; document.getElementById('c1').innerHTML = 'c'; document.getElementById('d1').innerHTML = 'd';
 document.getElementById('e1').innerHTML = 'e'; document.getElementById('f1').innerHTML = 'f'; document.getElementById('g1').innerHTML = 'g';
 document.getElementById('h1').innerHTML = 'h'; document.getElementById('l1').innerHTML = '1'; document.getElementById('l2').innerHTML = '2';
 document.getElementById('l3').innerHTML = '3'; document.getElementById('l4').innerHTML = '4'; document.getElementById('l5').innerHTML = '5';
 document.getElementById('l6').innerHTML = '6'; document.getElementById('l7').innerHTML = '7'; document.getElementById('l8').innerHTML = '8';
 document.getElementById('r1').innerHTML = '1'; document.getElementById('r2').innerHTML = '2'; document.getElementById('r3').innerHTML = '3';
 document.getElementById('r4').innerHTML = '4'; document.getElementById('r5').innerHTML = '5'; document.getElementById('r6').innerHTML = '6';
 document.getElementById('r7').innerHTML = '7'; document.getElementById('r8').innerHTML = '8';
 document.getElementById('flipbox').checked = false;
}
function a1h8flip()
{
 if ( document.getElementById('a8').innerHTML == 'a' ) h8a1(); else a1h8();
}

function escapepluses(ruchy)
{
 var a = replace(ruchy,"+","*");
 a = replace(a,"#","M");
 return replace(a,"=","Z");
}

function pozycja_fromFEN(fen)
{
 var width = 8, height = 8;
 if (!fen) { return ""; }
 var fenek = fen.split(' ');
 var horizontals = fenek[0].split('/');
 if (horizontals.length != height)
 { return ""; alert("The FEN string has "+horizontals.length+" horizontal lines."); return ""; }
 var pozycja = "";
 for (var y=height-1; y>=0; y--)
 {
  var hora = horizontals[y];
  while (hora != "")
  {
   switch(hora.charAt(0))
   {
    case 'K' : pozycja += "K"; break;
    case 'k' : pozycja += "k"; break;
    case 'Q' : pozycja += 'Q'; break;
    case 'q' : pozycja += 'q'; break;
    case 'R' : pozycja += 'R'; break;
    case 'r' : pozycja += 'r'; break;
    case 'B' : pozycja += 'B'; break;
    case 'b' : pozycja += 'b'; break;
    case 'N' : pozycja += 'N'; break;
    case 'n' : pozycja += 'n'; break;
    case 'P' : pozycja += 'P'; break;
    case 'p' : pozycja += 'p'; break;
    case '1' : pozycja += '_'; break;
    case '2' : pozycja += "__"; break;
    case '3' : pozycja += '___'; break;
    case '4' : pozycja += '____'; break;
    case '5' : pozycja += '_____'; break;
    case '6' : pozycja += '______'; break;
    case '7' : pozycja += '_______'; break;
    case '8' : pozycja += '________'; break;
    default  : return ""; alert("The FEN string has an illegal character: "+hora[0]); return "";
   }
   hora = hora.substring(1,hora.length);
  }
 }
 if (pozycja.length != width*height) { return ""; alert("The FEN string is corrupted."); return ""; }
 var s = "w"; if (fenek[1]=="b") s = "b";
 var white00=false,white000=false,black00=false,black000=false;
 if (fenek[2]) for (var i=0; i<fenek[2].length; i++)
 {
  switch(fenek[2].charAt(i))
  {
   case 'K' : white00 = true; break;
   case 'k' : black00 = true; break;
   case 'Q' : white000 = true; break;
   case 'q' : black000 = true; break;
  }
 }
 pozycja = castlingchar(white00,white000,black00,black000) + pozycja;
 var en = "0";
 if (fenek[3]) switch(fenek[3].charAt(0))
 {
  case '-': en = "0"; break;
  case 'a': en = '1'; break;
  case 'b': en = '2'; break;
  case 'c': en = '3'; break;
  case 'd': en = '4'; break;
  case 'e': en = '5'; break;
  case 'f': en = '6'; break;
  case 'g': en = '7'; break;
  case 'h': en = '8'; break;
  default: break; alert("Illegal enpassant data: "+fenek[3]);
 }
 return pozycja+en+s;
}

function wyparsujze(lancuch,wyrazenie)
{
 var rezu = wyrazenie.exec(lancuch);
 if (!rezu) return "";
 return rezu[0] + '_' + wyparsujze(lancuch.replace(rezu[0],""),wyrazenie);
}

function castlingchar(whiteOO,whiteOOO,blackOO,blackOOO)
{
 var a = 0;
 if (whiteOO) a += 1;
 if (whiteOOO) a += 2;
 if (blackOO) a += 4;
 if (blackOOO) a += 8;
 return String.fromCharCode(a+65);
}

function openwbeditor()
{
 var a = document.getElementById('PgnTextBox').value;
 a = replace(a,'[','');
 a = replace(a,']','');
 a = replace(a,'{','');
 a = replace(a,'}','');

 a = replace(a,/[ \s]{1,}/,'1q2w3e4');

 a = replace(a,'[','\t');
 a = replace(a,']','\n');
 a = replace(a,/\t\S{0,}\n/,'1q2w3e4');
 a = replace(a,'\t','[');
 a = replace(a,'\n',']');

 a = replace(a,'{','\t');
 a = replace(a,'}','\n');
 a = replace(a,/\t\S{0,}\n/,'1q2w3e4');
 a = replace(a,'\t','{');
 a = replace(a,'\n','}');

 a = replace(a,'(','\t');
 a = replace(a,')','\n');
 a = replace(a,/\t\S{0,}\n/,'1q2w3e4');
 a = replace(a,'\t','(');
 a = replace(a,'\n',')');

 a = replace(a,/(1q2w3e4){1,}/,' ');
 a = replace(a,/^ /,'');
 a = replace(a,/ $/,'');

 a = replace(a,'0-0-0','O-O-O');
 a = replace(a,'0-0','O-O');
 a = replace(a,'o-o-o','O-O-O');
 a = replace(a,'o-o','O-O');

 a = replace(a,'H','Q'); a = replace(a,'W','R'); a = replace(a,'G','B'); a = replace(a,'S','N');
 a = replace(a,'D','Q'); a = replace(a,'T','R'); a = replace(a,'L','B');

 var ru = /([KQRBNP]{0,1}([a-zA-Z]{0,1}\d{0,2}){0,1}[-x]{0,1}[a-zA-Z]\d{1,2}(={0,1}[QRBN]){0,1})|(O-O(-O){0,1})[\+#]{0,1}/;
 var moves = wyparsujze(a,ru) + "koniec";
 moves = moves.replace("_koniec","");
 moves = moves.replace("koniec","");

 var pozycja = pozycja_fromFEN(fen);
 var whostarts = "White";
 if (fen.indexOf(" b ") > -1) whostarts = "Black";

 var prefix = "http://www.apronus.com/chess/pgnviewer/";
 var width = 8;
 var height = 8;
 var ruchy = escapepluses(moves);
 var flip = ( document.getElementById('a8').innerHTML != 'a' );
 var url = prefix + "?";
 if (whostarts == "Black" || whostarts == "black") url += "s=black&";
 url += "p="+pozycja+"&";
 if (ruchy != "") url += "m="+ruchy+"&";
 if (flip) url += "f=1";
 if (url.charAt(url.length-1) == '&') url = url.substring(0,url.length-1);
 window.open(url);
}

function undobutton()
{
 clearindicatemove();
 clearcheck();
 var ruchyarray = sameruchyarray(document.getElementById("PgnTextBox").value);
 ruchyarray.pop(); 
 resetgarbochess(ruchyarray);
}

function forcemove()
{
 var ms = parseInt(el('TimePerMove').value); if (isNaN(ms)) ms = 1000; g_timeout = ms; // added because editor stopped respecting ms, always saw g_timeout = 1000
 var a = document.getElementById('nocomp').checked;
 document.getElementById('nocomp').checked = false;
 SearchAndRedraw();
 document.getElementById('nocomp').checked = a;
}

function absorbFEN()
{
 fen = document.getElementById('FenTextBox').value;
 UIChangeFEN();
 if (fen.indexOf(' w ')>-1) a1h8(); else h8a1();
 document.getElementById('PgnTextBox').value = '';
 document.getElementById('movelist').innerHTML = '&nbsp;';
 FENarray = new Array();
}

function leftarrowpressed()
{
	var n = g_stateline[0].halfmove;
	if (0 < n) eval(el('goleftbutton').getAttribute('onclick'));
}

function rightarrowpressed()
{
	var n = g_stateline[0].halfmove; var last = g_stateline.length-1;
	if (n < last)
	{
		eval(el('gorightbutton').getAttribute('onclick'));
		return;
	}
	
 var moves = sameruchyarray(document.getElementById('PgnTextBox').value);
 var lines;
 if (document.getElementById('edytorek'))
 {
  prettify(); prettify();
  lines = gettext();
  if (lines == '') { /*forcemove();*/ return; }
  lines = lines.split('\n');
 }
 else
 {
  lines = [].concat( zadanie[1] );
  if (lines.length == 0) { /*forcemove();*/ return; }
 }
 for (var i=0; i<lines.length; i++)
 {
  var line = sameruchyarray(lines[i]);
  if (line.length > 0)
  {
   var ruchy = moves.toString();
   var wariant = line.toString();
   if (ruchy == '' || wariant.indexOf(ruchy) == 0)
   {
    wariant = wariant.substr(ruchy.length);
    if (wariant == '') { /*forcemove();*/ return; }
    var ruch = sameruchyarray(wariant)[0];
    playmove(ruch);
    if (zadanie) showinsight(zadanie);
    return;
   } 
  }
 }
 /*forcemove();*/
}

function keywaspressed(e)
{
 if (e.keyCode == 13) // Enter
 {
  var next = document.getElementById('keeponsolving');
  if (next) if (next.style.display != 'none') startfen(zadanie);
 }

 if (e.keyCode == 27) // Esc
 {
  if (document.getElementById('edytorek')) closemodals();
  else
  {
   if (document.getElementById('controls').style.display != 'none')
    document.getElementById('controls').style.display = 'none';
   else
   {
    document.getElementById('showinsight').checked = false;
    toggleinsight();
   } 
  }
 }
 if (document.activeElement.nodeName == 'INPUT') return;
 if (document.activeElement.nodeName == 'TEXTAREA') return;
 e = e || event;
 if (e.keyCode == 8) { cofnijruch(); } // backspace
 if (e.keyCode == 37) { leftarrowpressed(); }
 if (e.keyCode == 39) { rightarrowpressed(); }
 if (e.keyCode == 48)
 {
	 g_stateline_refresh();
	 playnullmove();
	 onUserMove(NULLMOVE);
	 return;
 } 
 if (e.keyCode == 32) // spacebar
 {
	 e.preventDefault();
	 g_stateline_refresh(); // prevents js error when spacebar is pressed while dragging
	 if (g_puzzle_czy_allow_engine_help()) user_forcemove();
	 return false;
 }
}

function applyfigurines(ruchy)
{
 var king = svgmerida_notation('K');
 var queen = svgmerida_notation('Q');
 var rook = svgmerida_notation('R');
 var bishop = svgmerida_notation('B');
 var knight = svgmerida_notation('N');
 ruchy = replace(ruchy,'x','&times;');
 ruchy = replace(ruchy,'B','(bishop)');
 ruchy = replace(ruchy,'K',king);
 ruchy = replace(ruchy,'Q',queen);
 ruchy = replace(ruchy,'R',rook);
 ruchy = replace(ruchy,'(bishop)',bishop);
 ruchy = replace(ruchy,'N',knight);
 ruchy = replace(ruchy,'O-O-O','0-0-0');
 ruchy = replace(ruchy,'O-O','0-0');
 ruchy = replace(ruchy,'--','&ndash;&ndash;');
 return ruchy;
}

function html_moves_array(ruchy)
{
 if (ruchy.length == 0) return '';
 var blackstarts = fen.indexOf(' b ')>-1;
 var htmlmoves = '';
 var halfmoveindex = 0;
 var movenumber = Number( fen.split(' ')[5] );
 var style = 'display:inline; padding:0; margin:0';
 var open = ' <fieldset style="' + style + '">';
 var close = '</fieldset> ';
 if (blackstarts)
 {
  htmlmoves += open + movenumber + '&hellip;' + ruchy[0] + close;
  halfmoveindex = 1;
  movenumber++;
 }
 while (halfmoveindex < ruchy.length)
 {
  htmlmoves += open + movenumber + '.' + ruchy[halfmoveindex]; 
  halfmoveindex++;
  if (halfmoveindex < ruchy.length)
  {
   htmlmoves += '&nbsp;' + ruchy[halfmoveindex]; 
   halfmoveindex++;
   movenumber++;
  }
  htmlmoves += close; 
 }
 return applyfigurines(htmlmoves);
}

function appendruchygallery(element,ruchy)
{
 if (ruchy.length == 0) return false;
 var blackstarts = fen.indexOf(' b ')>-1;
 var halfmoveindex = 0;
 var movenumber = Number( fen.split(' ')[5] );
 var style = 'display:inline; padding:0; margin:0; cursor:pointer;';
 var ruchysofar = [];
 if (blackstarts)
 {
  ruchysofar.push(ruchy[0]);
  var f = document.createElement('fieldset');
  f.setAttribute('style',style);
  f.setAttribute('onclick','resetgarbochess(["'+ruchy[0]+'"]);galogg("notation-click","-");');
  f.innerHTML = applyfigurines( '<span>' + movenumber + '&hellip;' + ruchy[0] + '</span>' );
  element.appendChild(f);
  halfmoveindex = 1;
  movenumber++;
 }
 while (halfmoveindex < ruchy.length)
 {
  element.appendChild( document.createTextNode(' ') );
  ruchysofar.push( ruchy[halfmoveindex] );
  var f = document.createElement('fieldset');
  f.setAttribute('style',style);
  var s = document.createElement('span');
  s.setAttribute('onclick','resetgarbochess(["'+ruchysofar.join('","')+'"]);galogg("notation-click","-");');
  s.innerHTML = applyfigurines( movenumber + '.' + ruchy[halfmoveindex] );
  f.appendChild(s);
  halfmoveindex++;
  if (halfmoveindex < ruchy.length)
  {
   ruchysofar.push( ruchy[halfmoveindex] );
   var s = document.createElement('span');
   s.setAttribute('onclick','resetgarbochess(["'+ruchysofar.join('","')+'"]);galogg("notation-click","-");');
   s.innerHTML += '&nbsp;' + applyfigurines( ruchy[halfmoveindex] );
   f.appendChild(s); 
   halfmoveindex++;
   movenumber++;
  }
  element.appendChild(f); 
 }
 return true; 
}

function finalmove4gallery(ruchy)
{
 if (ruchy.length == 0) return null;
 var FEN = FENarray[FENarray.length-1];
 var blacktomove = FEN.indexOf(' b ')==-1;
 var halfmoveindex = ruchy.length-1;
 var movenumber = Number( FEN.split(' ')[5] );
 var czyfirstmove = movenumber == Number( g_stateline[0].FEN.split(' ')[5] );
 var style = 'display:inline; padding:0; margin:0;';
 var numerek = (blacktomove) ? (movenumber + '.') : ( (czyfirstmove && ruchy.length==1) ? (movenumber+'&hellip;') : '' );
  var f = newel('fieldset'); f.setAttribute('style',style);
  var s = newel('span'); s.id = 'halfmove'+(halfmoveindex+1);
  s.style.cursor = 'pointer'; s.className = 'removetaphighlight';
  s.setAttribute('onclick','g_stateline_goto('+(halfmoveindex+1)+'); vibrate("NotationClick"); galogg("notation-click","-");');
  //s.ontouchstart = function(e) { e.preventDefault(); e.stopPropagation(); eval(this.getAttribute('onclick')); return false; };
  // touchstart gets in the way of finger scrolling
  s.innerHTML = applyfigurines( numerek + ruchy[halfmoveindex] );
  f.appendChild(s);
 return f;
}
/*
function finalmove4gallery(ruchy)
{
 if (ruchy.length == 0) return null;
 var FEN = FENarray[FENarray.length-1];
 var blacktomove = FEN.indexOf(' b ')==-1;
 var halfmoveindex = ruchy.length-1;
 var movenumber = Number( FEN.split(' ')[5] );
 var style = 'display:inline; padding:0; margin:0;';
 var numerek = (blacktomove) ? (movenumber + '.') : ( (movenumber==1 && ruchy.length==1) ? (movenumber+'&hellip;') : '' );
  var f = newel('fieldset'); f.setAttribute('style',style);
  var s = newel('span'); s.id = 'halfmove'+(halfmoveindex+1);
  s.style.cursor = 'pointer'; s.className = 'removetaphighlight';
  s.setAttribute('onclick','g_stateline_goto('+(halfmoveindex+1)+'); vibrate("NotationClick"); galogg("notation-click","-");');
  //s.ontouchstart = function(e) { e.preventDefault(); e.stopPropagation(); eval(this.getAttribute('onclick')); return false; };
  // touchstart gets in the way of finger scrolling
  s.innerHTML = applyfigurines( numerek + ruchy[halfmoveindex] );
  f.appendChild(s);
 return f;
}
*/

function resetgarbochess(ruchyarray)
{
 newbutton();
 try { clearalert(); } catch(err) { };
 if (ruchyarray.length == 0) return;
 for (var i=0; i<ruchyarray.length; i++) playmove(ruchyarray[i]);
 if (zadanie) showinsight(zadanie); else updatenicelines();
}

function setinnerfen(fen)
{ 
 var result = InitializeFromFen(fen);
 if (result.length != 0) return result;
 g_allMoves = [];
 EnsureAnalysisStopped();
 InitializeBackgroundEngine();
 g_playerWhite = !!g_toMove;
 g_backgroundEngine.postMessage("position " + GetFen());
 console.log('background engine initialized in setinnerfen '+fen);
 return false;
}

function fenaftermoves(ruchyarray)
{
 var saveruchy = sameruchyarray(document.getElementById('PgnTextBox').value);
 if (setinnerfen(fen)) return alert('setinnerfen error');
 var ruch;
 //var stos = [];
 while( ruchyarray.length > 0 )
 {
  ruch = ruchyarray.shift();
  var moves = GenerateValidMoves();
  var move = null;
  for (var i=0; i<moves.length; i++)
   if ( GetMoveSAN(moves[i]) == ruch ) move = moves[i];
  if (move == null) return alert('illegal move in fenaftermoves');
  //stos.push(move);
  MakeMove(move);
 }
 var ret = GetFen();
 //while( stos.length > 0 ) UnmakeMove(stos.pop());
 resetgarbochess(saveruchy);
 return ret;  
}

/*
function applySVGbackground(a,svg)
{
	var div = newel('div'); div.appendChild(svg);
	var svgcode = div.innerHTML;
	a.style.backgroundImage = "url('data:image/svg+xml;utf8,"+escape(svgcode)+"')";
	a.style.backgroundSize = 'contain';
	a.style.backgroundRepeat = 'no-repeat';
	a.style.backgroundPosition = 'center';
}
*/
function applySVGbackground(a,svg)
{
	var div = newel('div'); div.appendChild(svg);
	var svgcode = div.innerHTML;
	a.style.backgroundImage = "url('data:image/svg+xml;base64,"+window.btoa(svgcode)+"')";
	a.style.backgroundSize = 'contain';
	a.style.backgroundRepeat = 'no-repeat';
	a.style.backgroundPosition = 'center';
}

function styleButton(bu)
{
	bu.style.backgroundColor = '#fefefe';
	bu.style.width = '32px';
	bu.style.height = '24px';
	bu.style.padding = '0'; bu.style.marginLeft = '0'; bu.style.marginRight = '1em';
	bu.style.border = 'none'; bu.style.outline = '1px solid #dddddd';
	bu.style.cursor = 'pointer';
	bu.className = 'navibutton';
}

function GoLeftButton()
{
	var bu = newel('button'); bu.id = 'goleftbutton';
	bu.setAttribute('onclick','g_stateline_goto(g_stateline[0].halfmove-1); vibrate("NaviButton"); galogg("NaviButton","left");');
	touchstart_from_click(bu);
	styleButton(bu);
	bu.innerHTML = '&nbsp;'; applySVGbackground(bu,navitriangle('left'));
	return bu;
}

function GoRightButton()
{
	var bu = newel('button'); bu.id = 'gorightbutton';
	bu.setAttribute('onclick','g_stateline_goto(g_stateline[0].halfmove+1); vibrate("NaviButton"); galogg("NaviButton","right");');
	touchstart_from_click(bu);
	styleButton(bu);
	bu.innerHTML = '&nbsp;'; applySVGbackground(bu,navitriangle('right'));
	return bu;
}

function BackButton()
{
	var bu = newel('button'); bu.id = 'dobrycofnijbutton';
	bu.setAttribute('onclick','galogg(\"backbutton\",ilemoves()); cofnijruch(); vibrate("NaviButton");');
	touchstart_from_click(bu);
	bu.style.marginRight = '1em'; bu.style.marginBottom = '0.5em';
	styleButton(bu);
	bu.innerHTML = '&nbsp;';
	applySVGbackground(bu,backtriangle());//bu.appendChild(backtriangle());
	return bu;	
}

function ResignButton()
{
	var bu = newel('button'); bu.id = 'resignbutton';
	bu.setAttribute('onclick','pozadaniu = false; undoids(); galogg(\"resign\",ilemoves()); hidebackbuttons(); startfen(zadanie); vibrate("NaviButton");');
	touchstart_from_click(bu);
	bu.style.marginRight = '1em'; bu.style.marginBottom = '0.5em';
	styleButton(bu);
	bu.innerHTML = '&nbsp;';
	applySVGbackground(bu,resigntriangle()); //bu.appendChild(resigntriangle()); //bu.innerHTML += ' '+ResignText();
	return bu;
}

function g_stateline_updatebuttons()
{
	var n = g_stateline[0].halfmove; var last = g_stateline.length-1;
	if (last > 0) showbackbuttons(); else hidebackbuttons();
	 el('goleftbutton').style.visibility = (last > 0) ? 'visible' : 'hidden';
	el('gorightbutton').style.visibility = (last > 0) ? 'visible' : 'hidden';
	 el('goleftbutton').disabled = (n > 0) ? false : true;
	el('gorightbutton').disabled = (n < last) ? false : true;
	 el('goleftbutton').style.opacity = (n > 0) ? '1' : '0.5';
	el('gorightbutton').style.opacity = (n < last) ? '1' : '0.5';
	if (last == 0)
	{
		plusbuttonspan = el('plusbuttonspan');
		if (plusbuttonspan) plusbuttonspan.parentElement.removeChild(plusbuttonspan);
	}
	var st = g_puzzle_state(); var shownavibuttons = st == 'editor' || st == 'nopuzzle';
	if (!shownavibuttons)
	{ el('goleftbutton').style.display = 'none'; el('gorightbutton').style.display = 'none'; }
	else
	{ el('goleftbutton').style.display = 'inline-block'; el('gorightbutton').style.display = 'inline-block'; }
}

function g_stateline_update_movelistbanner()
{
	//var last = g_stateline.length-1;
	//if (last > 0) movelistbanner_hide(); else movelistbanner_show();
}



function ResignButtonHTML()
{
	var div = newel('div'); div.appendChild(ResignButton()); return div.innerHTML;
}

function cofnijruchpostaremu()
{
 try { clearalert(); } catch(err) { };
 undobutton();
 if (el('bothbox').checked) return;
 var whitenow = GetFen().indexOf(' w ')>-1;
 var whitestarts = fen.indexOf(' w ')>-1;
 if (whitenow != whitestarts) undobutton();
}

function g_stateline_takeback()
{
	// two halfmoves are taken back only if the last one is not human and the prelast one is human
	var last = g_stateline.length-1;
	if (last == 0) return;
	if (last == 1 || g_stateline[last].whose == 'user') { g_stateline_halfmoveback(); return; }
	if (g_stateline[last-1].whose == 'user') { g_stateline_halfmoveback(); g_stateline_halfmoveback(); return; }
	g_stateline_halfmoveback();
}

function cofnijruch()
{
	g_stateline_takeback(); return;
	//cofnijruchpostaremu(); return;
	//if (el('PgnTextBox').value.indexOf('--')>-1) { cofnijruchpostaremu(); return; }
	cofnijpolruch();
	if (zadanie && !pozadaniu && !el('bothbox').checked)
	{
		if (encodedlines == '' && successrule == '') return;
		var whitenow = GetFen().indexOf(' w ')>-1;
		var whitestarts = fen.indexOf(' w ')>-1;
		if (whitenow != whitestarts) cofnijpolruch();
	}
}

function cofnijpolruch()
{
	g_stateline_halfmoveback();
}
function BackButtonHTML()
{
	var div = newel('div'); div.appendChild(BackButton()); return div.innerHTML;
}

function showbackbuttons()
{
	if (el('resignbutton') && el('dobrycofnijbutton'))
	{
		el('resignbutton').style.visibility = 'visible';
		el('dobrycofnijbutton').style.visibility = 'visible';
	}
}
function hidebackbuttons()
{
	if (el('resignbutton') && el('dobrycofnijbutton'))
	{
		el('resignbutton').style.visibility = 'hidden';
		el('dobrycofnijbutton').style.visibility = 'hidden';
	}
}

function updatemoves_clear()
{
	// same as calling updatemoves() with empty el('PgnTextBox')
	updateczyjruch();
	el('movelist').innerHTML = '';
	if (el('nicelinescontainer')) updatenicelines();
}

/*
function updatemoves()
{
 var style = 'margin-right:1em; margin-bottom:0.5em;';
 var startbutton = "<button onclick='newbutton();' style='"+style+"' title='back to initial position'>|&#9668;</button>";
 var backbutton = "<button onclick='undobutton();' style='"+style+"' title='take back last move'>&#9668;</button>";
 var addbutton = "<button onclick='addcurrentline();' style='"+style+"' title='add current line'>add</button>";
 var plusbutton = "<button id='plusbutton' onclick='addcurrentline();' style='"+style+"' title='add current line'>&nbsp;+&nbsp;</button>";
 //var resignbutton = "<button onclick='pozadaniu = false; undoids(); galogg(\"resign\",ilemoves()); startfen(zadanie);' style='"+style+"' >|&#9668; "+ResignText()+"</button>";
 var resignbutton = ResignButtonHTML();
 
 var buttons;// = startbutton + backbutton;
 if (document.getElementById('edytorek') == null)
 {
  plusbutton = '';
  backbutton = BackButtonHTML();
  buttons = resignbutton + backbutton + '<br>';
 }
 else
 {
  buttons = startbutton + backbutton + addbutton + '<br>';
 }
  
 updateczyjruch();
 var moves = document.getElementById('PgnTextBox').value;
 var movelist = document.getElementById('movelist');
 movelist.innerHTML = ''; 
 moves = sameruchyarray(moves);
 if (moves.length > 0) movelist.innerHTML = buttons;
 appendruchygallery(document.getElementById('movelist'),moves);
 if (moves.length > 0) document.getElementById('movelist').innerHTML += ' ' + plusbutton;
 
 if (document.getElementById('nicelinescontainer')) updatenicelines();
 
 return moves;
}
*/

function plusbutton()
{
 var style = 'margin-right:1em; margin-bottom:0.5em;';
 var plusbutton = "<button id=plusbutton onclick='addcurrentline();' style='"+style+"' title='add current line'>&nbsp;+&nbsp;</button>";
 var span = newel('span'); span.innerHTML = plusbutton; span.id = 'plusbuttonspan';
 span.style.marginLeft = '0.5em';
 return span;
}

function updatemoves_push()
{
 updateczyjruch();
 var moves = sameruchyarray(el('PgnTextBox').value);
 var finalmove = finalmove4gallery(moves);
 var movelist = el('movelist');
 var isblack = FENarray[FENarray.length-1].indexOf(' w ')==-1;
 if (isblack)
 {
	 var fs = movelist.querySelectorAll('fieldset');
	 if (fs.length > 0) 
	 {
		var f = fs[fs.length-1];
		f.appendChild(document.createTextNode(' '));
		f.appendChild(finalmove);
	 }
	 else movelist.appendChild(finalmove);
 }
 else
 {
	 movelist.appendChild(document.createTextNode(' '));
	 movelist.appendChild(finalmove);
 }
 if (el('edytorek'))
 {
	 //el('plusbutton').style.display = 'none';
	 var plusbuttonspan = el('plusbuttonspan');
	 if (plusbuttonspan) plusbuttonspan.parentElement.removeChild(plusbuttonspan);
	 movelist.appendChild(plusbutton());
 }
 if (document.getElementById('nicelinescontainer')) updatenicelines();
 showbackbuttons();
 g_stateline_updatemoves_scroll();
}

function updatemoves_pop()
{
 updateczyjruch();
 var message = el('movelistmessage'); if (message) message.parentElement.removeChild(message);
 //var moves = sameruchyarray(el('PgnTextBox').value);
 var movelist = el('movelist');
 var fs = movelist.querySelectorAll('fieldset');
 var f = fs[fs.length-1];
 f.parentElement.removeChild(f);
 if (document.getElementById('nicelinescontainer')) updatenicelines();
 if (g_allMoves.length == 0) hidebackbuttons();
}

function g_stateline_updatemoves_scroll()
{
	//if (isFirefox()) return;
	var n = g_stateline[0].halfmove; var last = g_stateline.length-1;
	if (last == 0) return;
	var movelist = el('movelist');
	movelist.style.overflowY = 'hidden';
	if (movelist.clientHeight < movelist.scrollHeight)
	{	
		if (n == 0) n = 1;
		el('halfmove'+n).scrollIntoView(false);
		movelist.style.overflowY = 'scroll';
		movelist.style.overflowX = 'visible';
	}
	else // no need to scroll
	{
		movelist.style.overflowX = 'visible';
		movelist.style.overflowY = 'visible';
	}
}


/*function updatemoves_scroll()
{
	return 7/aass;
	if (isFirefox()) return;
	var movelist = el('movelist');
	movelist.style.overflowY = 'hidden';
	if (movelist.clientHeight < movelist.scrollHeight)
	{
		var end = newel('p'); end.innerHTML = '-';
		end.style.color = 'rgb(255,255,255,0)';
		movelist.appendChild(end);
		//end.scrollIntoView(false);
		movelist.scrollTop = 2*movelist.scrollHeight;
		movelist.removeChild(end);
		movelist.style.overflowY = 'scroll';
		movelist.style.overflowX = 'visible';
	}
	else
	{
		movelist.style.overflowX = 'visible';
		movelist.style.overflowY = 'visible';
	}
	//{
	//	el('halfmove'+g_stateline[0].halfmove).scrollIntoView(false);
	//	var msg = el('movelistmessage'); if (msg) msg.scrollIntoView(false);
	//	movelist.style.overflow = 'scroll'; movelist.scrollIntoView(false);
	//	movelist.style.overflowX = 'visible';
	//}
}*/

/* this allows e2e4 -- f2e3
function makenullmove()
{
 var fennow = GetFen();
 if (fennow.indexOf(' w ')>-1) fennow = fennow.replace(' w ',' b ');
                          else fennow = fennow.replace(' b ',' w ');
 var result = InitializeFromFen(fennow);
 if (result.length != 0) return result;
 EnsureAnalysisStopped();
 InitializeBackgroundEngine();
 g_backgroundEngine.postMessage("position " + GetFen());
 //document.getElementById("PgnTextBox").value += "null" + " ";
 //aftermove();
 return false;
}
*/

function makenullmove()
{
 var fennow = GetFen();
 if (fennow.indexOf(' w ')>-1) fennow = fennow.replace(' w ',' b ');
                          else fennow = fennow.replace(' b ',' w ');

 const fenek = fennow.split(' ');
 fenek[3] = '-'; // clear enpassant square
 fennow = fenek.join(' ');

 var result = InitializeFromFen(fennow);
 if (result.length != 0) return result;
 EnsureAnalysisStopped();
 InitializeBackgroundEngine();
 g_backgroundEngine.postMessage("position " + GetFen());
 return false;
}

function playnullmove()
{
	if (g_inCheck) { alert('You are in check!'); return; }
	g_stateline_makemove(null); return;
/* var fennow = GetFen();
 if (fennow.indexOf(' w ')>-1) fennow = fennow.replace(' w ',' b ');
                          else fennow = fennow.replace(' b ',' w ');
 var result = InitializeFromFen(fennow);
 if (result.length != 0) return result;
 EnsureAnalysisStopped();
 InitializeBackgroundEngine();
 g_backgroundEngine.postMessage("position " + GetFen());
 document.getElementById("PgnTextBox").value += "--" + " ";
 aftermove();
 return false;*/
}

function clearcheck()
{
 /*for (var y = 0; y < 8; ++y) for (var x = 0; x < 8; ++x)
 {
  var td = g_uiBoard[y * 8 + x];
  td.style.backgroundImage = 'none';
  td.style.transform = 'none';
 }*/
 console.log('clearcheck() did nothing');
 //RedrawPieces();
}

function clearindicatemove()
{
 for (y = 0; y < 8; ++y) for (x = 0; x < 8; ++x) g_uiBoard[y * 8 + x].querySelector('svg').style.boxShadow = 'none';
}

function indicatemove(move,pramove)
{
	if (move == NULLMOVE) return "--";
	if (true)
	{
		clearindicatemove();
		var OO = (move & moveflagCastleKing);
		var OOO = (move & moveflagCastleQueen);
		var enpassant = (move & moveflagEPC);
		var promotion = (move & moveflagPromotion);
		var from = move & 0xFF, to = (move >> 8) & 0xFF;
		var piece = ["", "", "N", "B", "R", "Q", "K", ""][g_board[from] & 0x7];
		var flipped = el('flipbox').checked;
		function xcoord(square) { var xx = 1 + (square & 0xF) - 4; return (flipped) ? (9-xx) : xx;  }
		function ycoord(square) { var yy = (9 - (square >> 4)) + 1; return (flipped) ? (9-yy): yy; }
		var fromX = xcoord(from), fromY = ycoord(from), toX = xcoord(to), toY = ycoord(to);
		fromtd = g_uiBoard[(8-fromY) * 8 + (fromX-1)];
		totd = g_uiBoard[(8-toY) * 8 + (toX-1)];
		var thick = g_cellSize * 0.25 + 'px';
		fromtd.querySelector('svg').style.boxShadow = 'inset 0 0 '+thick+' #444444'; // 10px
		totd.querySelector('svg').style.boxShadow = 'inset 0 0 '+thick+' #444444';
	}
	clearlastarrows();
	arrowmove(move);
	if (pramove) arrowmove(pramove,'next to last');
}

function arrowmove(move,nexttolast)
{
	var color = 'rgb(125,125,125)';
	var b = (nexttolast) ? (g_cellSize*0.05) : (g_cellSize*0.08);
	var lastarrow = readyarrow(move,color,b);
	lastarrow.setAttribute('class','lastarrow');
	lastarrow.style.opacity = (nexttolast) ? (''+(0.3*0.5)) : '0.5';
	el('arplat').appendChild(lastarrow);
}

function clearlastarrows()
{
	if (!el('arplat')) return;
	var a = arplat.querySelectorAll('.lastarrow');
	if (a.length == 0) return;
	for (var i=a.length-1; i>=0; i--) el('arplat').removeChild(a[i]);
}

function readyarrow(move,color,b)
{
	var from = move & 0xFF, to = (move >> 8) & 0xFF;
	var flipped = el('flipbox').checked;
	function xcoord(square) { var xx = 1 + (square & 0xF) - 4; return (flipped) ? (9-xx) : xx;  }
	function ycoord(square) { var yy = (9 - (square >> 4)) + 1; return (flipped) ? (9-yy): yy; }
	var fromX = xcoord(from), fromY = ycoord(from), toX = xcoord(to), toY = ycoord(to);
	var arrows = fromX+'Q'+fromY+'Q'+toX+'Q'+toY+'Q255Q0Q0';
	var width = 8, height = 8, flip = false, size = g_cellSize;
	var x1 = fromX, y1 = fromY, x2 = toX, y2 = toY;
	x1 = (x1-1)*size + Math.floor(size/2); y1 = (height-y1)*size + Math.floor(size/2);
	x2 = (x2-1)*size + Math.floor(size/2); y2 = (height-y2)*size + Math.floor(size/2);
	var h = 15;//10; // arrow head height -- 13
	var a = 10; // 2a arrow base length -- 7
	//var b = 4;//1; // 2b arrow line width -- 1
	//if (nexttolast) b = 3;
	var z = Math.pow( 0.0+Math.pow((y2-y1),2)+Math.pow((x1-x2),2) , -0.5);
	var Kx = x2 - h*z*(x2-x1); var Ky = y2 - h*z*(y2-y1);
	var KAx = Kx + (b)*z*(y2-y1); var KAy = Ky + (b)*z*(x1-x2);
	var KBx = Kx - (b)*z*(y2-y1); var KBy = Ky - (b)*z*(x1-x2);
	var Cx = Kx + (a)*z*(y2-y1); var Cy = Ky + (a)*z*(x1-x2);
	var Dx = Kx - (a)*z*(y2-y1); var Dy = Ky - (a)*z*(x1-x2);
	var Ax = x1 + b*z*(y2-y1); var Ay = y1 + b*z*(x1-x2);
	var Bx = x1 - b*z*(y2-y1); var By = y1 - b*z*(x1-x2);
	var points = ''+Ax+','+Ay+' '+KAx+','+KAy+' '+Cx+','+Cy+' '+x2+','+y2+' '+Dx+','+Dy+' '+KBx+','+KBy+' '+Bx+','+By;
	var a = svgelement('polygon');
	a.setAttribute('points',points); a.setAttribute('fill',color); //a.setAttribute('stroke-width',0);
	return a;
}

function clearredarrows()
{
	if (!el('arplat')) return;
	var a = arplat.querySelectorAll('.redarrow');
	if (a.length == 0) return;
	for (var i=a.length-1; i>=0; i--) el('arplat').removeChild(a[i]);
}

function clearcheckarrows()
{
	if (!el('arplat')) return;
	var a = arplat.querySelectorAll('.checkarrow');
	if (a.length == 0) return;
	for (var i=a.length-1; i>=0; i--) el('arplat').removeChild(a[i]);	
}

function checkarrow()
{
	var color = 'rgb(255,0,0)', b = 3, b = (g_cellSize*0.06);
	var arrow = readyarrow(g_kingchecked,color,b);
	arrow.setAttribute('class','checkarrow');
	arrow.style.opacity = '0.5';
	el('arplat').appendChild(arrow);
	setTimeout(clearcheckarrows,1000);
}

function showkingtake(move)
{
	//var OO = (move & moveflagCastleKing);
	//var OOO = (move & moveflagCastleQueen);
	//var enpassant = (move & moveflagEPC);
	//var promotion = (move & moveflagPromotion);
	var color = 'rgb(255,0,0)';
	var b = (g_cellSize*0.06);
	var redarrow = readyarrow(move,color,b);
	redarrow.className = 'redarrow';
	redarrow.setAttribute('class','redarrow');
	redarrow.style.opacity = '0.5';
	clearredarrows();
	el('arplat').appendChild(redarrow);
	setTimeout(clearredarrows,1000);
}

function vibrate(e)
{
	var pattern = [];
	pattern['selectedPiece'] = [50];
	pattern['onUserMove'] = [50];
	pattern['illegalMove'] = [100,50,100];
	pattern['prelegalIllegal'] = [100,50,100,50,100];
	pattern['GameOver'] = [200,100,200,100,100];
	pattern['WrongColor'] = [100,50,200];
	pattern['BlockedChessman'] = [100,100,100];
	pattern['EngineThinking'] = [300];
	pattern['NaviButton'] = [50];
	pattern['NotationClick'] = [50];
	//console.log('vibration for '+e);
	try { window.navigator.vibrate(pattern[e]); } catch(err) {}
}

function disablebutton(bu)
{
	bu.disabled = true;
	bu.style.pointerEvents = 'none';
	bu.style.cursor = 'wait';
}
function enablebutton(bu)
{
	bu.disabled = false;
	bu.style.pointerEvents = 'auto';
	bu.style.cursor = 'pointer';
}

var g_enginethinking = false;
function enginethinkingstyle()
{
	g_enginethinking = true;
	document.body.style.cursor = 'wait';
	el('chessboardtable').style.cursor = 'wait';
	bottomczyjruch.style.transform = 'translateX(-1px) scale(2)';
	topczyjruch.style.transform = 'translateX(-1px) scale(2)';
	el('movelist').style.pointerEvents = 'none';
	disablebutton(el('resignbutton'));
	disablebutton(el('dobrycofnijbutton'));
	disablebutton(el('goleftbutton'));
	disablebutton(el('gorightbutton'));
	el('controlstd').className = 'spinning';
}
function userthinkingstyle()
{
	g_enginethinking = false;
	document.body.style.cursor = 'auto';
	el('chessboardtable').style.cursor = 'auto';
	bottomczyjruch.style.transform = 'translateX(-2px) scale(1)';
	topczyjruch.style.transform = 'translateX(-2px) scale(1)';
	el('movelist').style.pointerEvents = 'auto';
	enablebutton(el('resignbutton'));
	enablebutton(el('dobrycofnijbutton'));
	enablebutton(el('goleftbutton'));
	enablebutton(el('gorightbutton'));
	g_stateline_updatebuttons();
	el('controlstd').className = '';
}

function ResultPanel_adjustfont()
{
	var a = el('ResultPanel');
	if (a) if (a.clientHeight < a.scrollHeight)
	{
		var fontsize = parseInt(a.style.fontSize);
		a.style.fontSize = (fontsize-1) + 'px';
		setTimeout(ResultPanel_adjustfont,1);
	}
}

function g_stateline_update_resultpanel()
{
	if (el('ResultPanel')) document.body.removeChild(el('ResultPanel'));
	if ((parseInt(mode) & 128) == 128) return; // no result setting
	var n = g_stateline[0].halfmove;
	var message = g_stateline[n].message;
	if (message)
	{
		var resultpanel = newel('div'); resultpanel.id = 'ResultPanel';
		resultpanel.style.position = 'absolute';
		var SE = $(el('bottomczyjruch')).offset(), NW = $(el('chessboardtable')).offset();
		var margin = 0;
		var minX = NW.left - margin, minY = NW.top - margin, maxX = SE.left + margin, maxY = SE.top + margin;
		resultpanel.style.left = minX + 'px';
		resultpanel.style.top = minY - el('topczyjruch').clientHeight + 'px';
		resultpanel.style.height = el('topczyjruch').clientHeight + 'px';
		resultpanel.style.overflow = 'hidden';
		resultpanel.style.width = (maxX-minX) + 'px';
		resultpanel.style.textAlign = 'center';
		resultpanel.style.paddingLeft = '0'; resultpanel.style.paddingRight = '0';
		resultpanel.style.background = 'rgb(255,255,255)';
		resultpanel.style.color = 'black';
		var color = 'black';
		if (message.indexOf('kolorred')>-1) color = 'red';
		if (message.indexOf('kolorgreen')>-1) color = 'green';
		resultpanel.style.boxShadow = '0 0 0.8em 0 '+color;
		resultpanel.innerHTML = message;		
		var fieldset = resultpanel.querySelector('fieldset');
		if (fieldset) fieldset.parentElement.removeChild(fieldset); // removing 1-0, 0-1, 1/2-1/2 to make it narrower
		document.body.appendChild(resultpanel);
		resultpanel.style.fontSize = '16px';
		setTimeout(ResultPanel_adjustfont,0);
	}
}


function undecorate()
{
	RedrawBoard(); updateczyjruch();
	if (el('ResultPanel')) document.body.removeChild(el('ResultPanel'));
}


function resizeboard(size)
{
	size = size || 396;
	var A = size; var minsize = 8*27 + 2*18;
	if (A < minsize) A = minsize;
	el('chessboard').style.width = A + 'px';
	el('chessboard').style.height = A + 'px';
	g_cellSize = Math.floor((A-2*18)/8);
	var nadmiar = (A-2*18)%8;
	var marginleft = Math.floor(nadmiar/2);
	el('chessboard').innerHTML = '';
	el('chessboard').appendChild(boardcontainer());
	if (flipcyfra=='1') h8a1();
	addcontrolstd();
	el('boardcontainer').style.marginLeft = marginleft+'px';
	g_stateline_refresh();
	return A;
}

function computedmargin(elem)
{
	if (!elem.firstElementChild) return 0;
	var a = window.getComputedStyle(elem.lastElementChild, null);
	var marginBottom = a.getPropertyValue("margin-bottom");
	var a = window.getComputedStyle(elem.firstElementChild, null);
	var marginTop = a.getPropertyValue("margin-top");
	return parseInt(marginBottom)+parseInt(marginTop);
}

function resizepuzzlediv_portrait()
{
 document.body.style.margin = '0';
 var a = el('puzzlediv');
 var topp = 1, bottom = 4, left = 3, right = 3;
 a.style.padding = topp+'px '+right+'px '+bottom+'px '+left+'px';
 a.style.height = (window.innerHeight - beforepuzzlediv.clientHeight - (topp+bottom+1)) + 'px';
 a.style.width = (window.innerWidth - (left+right+1)) + 'px';

 document.body.style.overflow = 'hidden';
 el('puzzlediv').style.overflow = 'hidden';
 
 var gap = 10; // gap between the board and the buttons, and between the buttons and the moves
 var buttonsheight = 24; // height of the buttons not counting margin
 var belowboard = 2*gap + 2*buttonsheight + 10; // minimal height below the board fully visible inside puzzlediv
 var w = el('puzzlediv').clientWidth - left - right;
 var h = el('puzzlediv').clientHeight - topp - bottom - el('prepuzzle').clientHeight - belowboard;
 h -= computedmargin(el('h1header'));
 var size = (h<w) ? h : w;
 size = resizeboard(size); // returns the computed size
 if (size != ((h<w) ? h : w)) console.log('using minimal board size instead');
 el('chessboard').style.display = 'block';
 el('chessboard').style.margin = '0 auto 0 auto'; //top right bottom left
 g_stateline_update_resultpanel();
 
 el('BelowOrBesideBoard').style.display = 'block';
 el('BelowOrBesideBoard').style.width = '100%';
 el('belowboardbuttons').style.display = 'block';
 el('belowboardbuttons').style.margin = '0';
 el('belowboardbuttons').style.textAlign = 'center';
 var buttons = el('belowboardbuttons').querySelectorAll('button');
 for (var i = buttons.length-1; i >= 0; i--)
 {
	 buttons[i].style.height = buttonsheight + 'px';
	 buttons[i].style.display = 'inline-block';
	 buttons[i].style.margin = gap+'px 1em '+gap+'px 0px';
 }
 g_stateline_updatebuttons();

 el('outputboard').style.display = 'block';
 el('outputboard').style.margin = '0';
 el('outputboard').style.width = '100%';
 var min = belowboard - 2*gap - buttonsheight;
 var max = h+belowboard - size - 2*gap - buttonsheight;
 if (min > max) console.log('min > max for movelist height');
 el('movelist').style.maxHeight = max + 'px';
 g_stateline_updatemoves_scroll();

 el('gallery').style.display = 'none'; 
 
 el('puzzlediv').style.position = 'relative';
 el('alert').style.position = 'absolute'; el('alert').style.top = '0'; el('alert').style.left = '0';
 el('alert').style.background = '#eee'; el('alert').style.width = '100%';
 el('closealertbutton').style.visibility = 'visible';
 
 el('puzzlediv').style.overflow = 'hidden';
 if (el('puzzlediv').style.clientHeight < el('puzzlediv').style.scrollHeight) console.log('puzzlediv is overflowing');
 //console.log('puzzlediv resized to portrait');
}

function resizepuzzlediv_landscape()
{
 document.body.style.margin = '0';
 var a = el('puzzlediv');
 var topp = 16, bottom = 16, left = 8, right = 0;
 a.style.padding = topp+'px '+right+'px '+bottom+'px '+left+'px';
 a.style.height = (window.innerHeight - beforepuzzlediv.clientHeight - (topp+bottom+1)) + 'px';
 a.style.width = (window.innerWidth - (left+right+1)) + 'px';

 document.body.style.overflow = 'hidden';
 el('puzzlediv').style.overflow = 'hidden';
 
 var PanelWidth = 19*16; // originally 19em
 var gap = 16; // originally 1em, gap between chessboard and panel, between buttons and movelist
 var buttonsheight = 24; // height of the buttons not counting margin
 
 var w = el('puzzlediv').clientWidth - left - right - PanelWidth - gap - 7; // last number is for good measure, it doesn't work without it
 var h = el('puzzlediv').clientHeight - topp - bottom - el('prepuzzle').clientHeight;
 h -= computedmargin(el('h1header'));
 var size = (w < h) ? w : h;
 size = resizeboard(size); // returns the computed size
 el('chessboard').style.display = 'inline-block';
 el('chessboard').style.margin = '0 '+gap+'px 0 0'; //top right bottom left
 g_stateline_update_resultpanel();

 el('BelowOrBesideBoard').style.display = 'inline-block';
 el('BelowOrBesideBoard').style.width = PanelWidth + 'px';
 el('belowboardbuttons').style.display = 'block';
 el('belowboardbuttons').style.margin = '0';
 el('belowboardbuttons').style.textAlign = 'left';
 var buttons = el('belowboardbuttons').querySelectorAll('button');
 for (var i = buttons.length-1; i >= 0; i--)
 {
	 buttons[i].style.height = buttonsheight + 'px';
	 buttons[i].style.display = 'inline-block';
	 buttons[i].style.margin = '0px 1em '+gap+'px 0px';
 }
 g_stateline_updatebuttons();

 el('outputboard').style.display = 'block';
 el('outputboard').style.height = size - buttonsheight - gap;
 el('outputboard').style.margin = '0';
 el('outputboard').style.width = '100%';
 var max = size - buttonsheight - 2*gap - 45; //45px for alertpanel
 el('movelist').style.maxHeight = max + 'px';
 el('movelist').style.margin = '0';
 g_stateline_updatemoves_scroll();

 el('alert').style.position = 'static';
 el('alert').style.background = 'none';
 el('alert').style.margin = gap+'px 0 0 0';
 el('closealertbutton').style.visibility = 'hidden';
 
 var gallery = el('gallery');
 var galw = w - size - 30; // originally gap was 30 here
 gallery.style.display = (galw < 334) ? 'none' : 'inline-block';
 gallery.style.width = galw + 'px'; 
 gallery.style.overflow = 'auto';
 gallery.style.height = size + 'px';
 
 //console.log('puzzlediv resized to landscape');
}


//2022-07-02
const indicate_prefen_move = () =>
{
	const garbomove_from_ruch = (ruch) =>
	{
		const fromX = 'abcdefgh'.indexOf(ruch[0]);
		const fromY = 8 - parseInt(ruch[1]);
		const toX = 'abcdefgh'.indexOf(ruch[2]);
		const toY = 8 - parseInt(ruch[3]);
		return (fromX+4) + ((fromY+2) << 4) + ((toX+4) << 8) + ((toY+2) << 12);
	}

	const startfen = g_stateline[0].FEN;
	const enpassant = startfen.split(' ')[3];
	if (enpassant.length === 2)
	{
		const file = enpassant[0];
		const white2move = startfen.split(' ')[1] == 'w';
		const ruch = (white2move) ? `${file}7${file}5` : `${file}2${file}4`;
		indicatemove(garbomove_from_ruch(ruch));
		return;
	}
	if (enpassant.length === 5 && enpassant[0] === '-')
	{
		const ruch = enpassant.substring(1); 
		indicatemove(garbomove_from_ruch(ruch));
		return;
	}
}

// https://github.com/MichalRyszardWojcik/szachydzieciom/issues/44
function initfromFEN(fen)
{
 if (pozafromFENtokens(fen) === '') { alert('wrong FEN'); return; }
 el('FenTextBox').value = fen;
 UIChangeFEN();
 if (fen.includes(' w ')) a1h8(); else h8a1();
 el('PgnTextBox').value = '';
 el('movelist').innerHTML = '&nbsp;';
 FENarray = new Array();
 updateczyjruch();
 flipcyfra = (fen.includes(' b ')) ? '1' : '0';
 transferfen = flipcyfra + replace(fen," ","_");
 transferfen = replace(transferfen,"/","X");
 matein = 0;
 puttext('');
 updatepuzzle();
 g_stateline_resetFEN(el('FenTextBox').value);
}

function user_forcemove()
{
 if (g_puzzle_czy_selfplay_exam())
 {
  if (confirm(forcemovealerttext())) forcemove();
  return;
 }
 forcemove();
}
//https://mail.google.com/mail/u/0/#search/support%40vdo.ai+tag/FMfcgzGllCkKKmzPPtFpXTDsJxJFNNNq
//Below is the banner tag for mobile with the dimensions 320x50:
//On desktop it is 160x600.
/*
<div id='b-apronus'></div><script>
(function(v, d, o, ai) {
ai = d.createElement('script');
ai.defer = true;
ai.async = true;
ai.src = v.location.protocol + o;
d.head.appendChild(ai);
})(window, document, '//a.vdo.ai/core/b-apronus/vdo.ai.js');
</script>
*/

const insert_vdo_tag_bapronus = (where) =>
{
	const div = document.createElement('div');
	div.id = 'b-apronus';
	where.append(div);
	(function(v, d, o, ai) {
	ai = d.createElement('script');
	ai.defer = true;
	ai.async = true;
	ai.src = v.location.protocol + o;
	d.head.appendChild(ai);
	})(window, document, '//a.vdo.ai/core/b-apronus/vdo.ai.js');
}
/*! jQuery v1.8.2 jquery.com | jquery.org/license */
(function(a,b){function G(a){var b=F[a]={};return p.each(a.split(s),function(a,c){b[c]=!0}),b}function J(a,c,d){if(d===b&&a.nodeType===1){var e="data-"+c.replace(I,"-$1").toLowerCase();d=a.getAttribute(e);if(typeof d=="string"){try{d=d==="true"?!0:d==="false"?!1:d==="null"?null:+d+""===d?+d:H.test(d)?p.parseJSON(d):d}catch(f){}p.data(a,c,d)}else d=b}return d}function K(a){var b;for(b in a){if(b==="data"&&p.isEmptyObject(a[b]))continue;if(b!=="toJSON")return!1}return!0}function ba(){return!1}function bb(){return!0}function bh(a){return!a||!a.parentNode||a.parentNode.nodeType===11}function bi(a,b){do a=a[b];while(a&&a.nodeType!==1);return a}function bj(a,b,c){b=b||0;if(p.isFunction(b))return p.grep(a,function(a,d){var e=!!b.call(a,d,a);return e===c});if(b.nodeType)return p.grep(a,function(a,d){return a===b===c});if(typeof b=="string"){var d=p.grep(a,function(a){return a.nodeType===1});if(be.test(b))return p.filter(b,d,!c);b=p.filter(b,d)}return p.grep(a,function(a,d){return p.inArray(a,b)>=0===c})}function bk(a){var b=bl.split("|"),c=a.createDocumentFragment();if(c.createElement)while(b.length)c.createElement(b.pop());return c}function bC(a,b){return a.getElementsByTagName(b)[0]||a.appendChild(a.ownerDocument.createElement(b))}function bD(a,b){if(b.nodeType!==1||!p.hasData(a))return;var c,d,e,f=p._data(a),g=p._data(b,f),h=f.events;if(h){delete g.handle,g.events={};for(c in h)for(d=0,e=h[c].length;d<e;d++)p.event.add(b,c,h[c][d])}g.data&&(g.data=p.extend({},g.data))}function bE(a,b){var c;if(b.nodeType!==1)return;b.clearAttributes&&b.clearAttributes(),b.mergeAttributes&&b.mergeAttributes(a),c=b.nodeName.toLowerCase(),c==="object"?(b.parentNode&&(b.outerHTML=a.outerHTML),p.support.html5Clone&&a.innerHTML&&!p.trim(b.innerHTML)&&(b.innerHTML=a.innerHTML)):c==="input"&&bv.test(a.type)?(b.defaultChecked=b.checked=a.checked,b.value!==a.value&&(b.value=a.value)):c==="option"?b.selected=a.defaultSelected:c==="input"||c==="textarea"?b.defaultValue=a.defaultValue:c==="script"&&b.text!==a.text&&(b.text=a.text),b.removeAttribute(p.expando)}function bF(a){return typeof a.getElementsByTagName!="undefined"?a.getElementsByTagName("*"):typeof a.querySelectorAll!="undefined"?a.querySelectorAll("*"):[]}function bG(a){bv.test(a.type)&&(a.defaultChecked=a.checked)}function bY(a,b){if(b in a)return b;var c=b.charAt(0).toUpperCase()+b.slice(1),d=b,e=bW.length;while(e--){b=bW[e]+c;if(b in a)return b}return d}function bZ(a,b){return a=b||a,p.css(a,"display")==="none"||!p.contains(a.ownerDocument,a)}function b$(a,b){var c,d,e=[],f=0,g=a.length;for(;f<g;f++){c=a[f];if(!c.style)continue;e[f]=p._data(c,"olddisplay"),b?(!e[f]&&c.style.display==="none"&&(c.style.display=""),c.style.display===""&&bZ(c)&&(e[f]=p._data(c,"olddisplay",cc(c.nodeName)))):(d=bH(c,"display"),!e[f]&&d!=="none"&&p._data(c,"olddisplay",d))}for(f=0;f<g;f++){c=a[f];if(!c.style)continue;if(!b||c.style.display==="none"||c.style.display==="")c.style.display=b?e[f]||"":"none"}return a}function b_(a,b,c){var d=bP.exec(b);return d?Math.max(0,d[1]-(c||0))+(d[2]||"px"):b}function ca(a,b,c,d){var e=c===(d?"border":"content")?4:b==="width"?1:0,f=0;for(;e<4;e+=2)c==="margin"&&(f+=p.css(a,c+bV[e],!0)),d?(c==="content"&&(f-=parseFloat(bH(a,"padding"+bV[e]))||0),c!=="margin"&&(f-=parseFloat(bH(a,"border"+bV[e]+"Width"))||0)):(f+=parseFloat(bH(a,"padding"+bV[e]))||0,c!=="padding"&&(f+=parseFloat(bH(a,"border"+bV[e]+"Width"))||0));return f}function cb(a,b,c){var d=b==="width"?a.offsetWidth:a.offsetHeight,e=!0,f=p.support.boxSizing&&p.css(a,"boxSizing")==="border-box";if(d<=0||d==null){d=bH(a,b);if(d<0||d==null)d=a.style[b];if(bQ.test(d))return d;e=f&&(p.support.boxSizingReliable||d===a.style[b]),d=parseFloat(d)||0}return d+ca(a,b,c||(f?"border":"content"),e)+"px"}function cc(a){if(bS[a])return bS[a];var b=p("<"+a+">").appendTo(e.body),c=b.css("display");b.remove();if(c==="none"||c===""){bI=e.body.appendChild(bI||p.extend(e.createElement("iframe"),{frameBorder:0,width:0,height:0}));if(!bJ||!bI.createElement)bJ=(bI.contentWindow||bI.contentDocument).document,bJ.write("<!doctype html><html><body>"),bJ.close();b=bJ.body.appendChild(bJ.createElement(a)),c=bH(b,"display"),e.body.removeChild(bI)}return bS[a]=c,c}function ci(a,b,c,d){var e;if(p.isArray(b))p.each(b,function(b,e){c||ce.test(a)?d(a,e):ci(a+"["+(typeof e=="object"?b:"")+"]",e,c,d)});else if(!c&&p.type(b)==="object")for(e in b)ci(a+"["+e+"]",b[e],c,d);else d(a,b)}function cz(a){return function(b,c){typeof b!="string"&&(c=b,b="*");var d,e,f,g=b.toLowerCase().split(s),h=0,i=g.length;if(p.isFunction(c))for(;h<i;h++)d=g[h],f=/^\+/.test(d),f&&(d=d.substr(1)||"*"),e=a[d]=a[d]||[],e[f?"unshift":"push"](c)}}function cA(a,c,d,e,f,g){f=f||c.dataTypes[0],g=g||{},g[f]=!0;var h,i=a[f],j=0,k=i?i.length:0,l=a===cv;for(;j<k&&(l||!h);j++)h=i[j](c,d,e),typeof h=="string"&&(!l||g[h]?h=b:(c.dataTypes.unshift(h),h=cA(a,c,d,e,h,g)));return(l||!h)&&!g["*"]&&(h=cA(a,c,d,e,"*",g)),h}function cB(a,c){var d,e,f=p.ajaxSettings.flatOptions||{};for(d in c)c[d]!==b&&((f[d]?a:e||(e={}))[d]=c[d]);e&&p.extend(!0,a,e)}function cC(a,c,d){var e,f,g,h,i=a.contents,j=a.dataTypes,k=a.responseFields;for(f in k)f in d&&(c[k[f]]=d[f]);while(j[0]==="*")j.shift(),e===b&&(e=a.mimeType||c.getResponseHeader("content-type"));if(e)for(f in i)if(i[f]&&i[f].test(e)){j.unshift(f);break}if(j[0]in d)g=j[0];else{for(f in d){if(!j[0]||a.converters[f+" "+j[0]]){g=f;break}h||(h=f)}g=g||h}if(g)return g!==j[0]&&j.unshift(g),d[g]}function cD(a,b){var c,d,e,f,g=a.dataTypes.slice(),h=g[0],i={},j=0;a.dataFilter&&(b=a.dataFilter(b,a.dataType));if(g[1])for(c in a.converters)i[c.toLowerCase()]=a.converters[c];for(;e=g[++j];)if(e!=="*"){if(h!=="*"&&h!==e){c=i[h+" "+e]||i["* "+e];if(!c)for(d in i){f=d.split(" ");if(f[1]===e){c=i[h+" "+f[0]]||i["* "+f[0]];if(c){c===!0?c=i[d]:i[d]!==!0&&(e=f[0],g.splice(j--,0,e));break}}}if(c!==!0)if(c&&a["throws"])b=c(b);else try{b=c(b)}catch(k){return{state:"parsererror",error:c?k:"No conversion from "+h+" to "+e}}}h=e}return{state:"success",data:b}}function cL(){try{return new a.XMLHttpRequest}catch(b){}}function cM(){try{return new a.ActiveXObject("Microsoft.XMLHTTP")}catch(b){}}function cU(){return setTimeout(function(){cN=b},0),cN=p.now()}function cV(a,b){p.each(b,function(b,c){var d=(cT[b]||[]).concat(cT["*"]),e=0,f=d.length;for(;e<f;e++)if(d[e].call(a,b,c))return})}function cW(a,b,c){var d,e=0,f=0,g=cS.length,h=p.Deferred().always(function(){delete i.elem}),i=function(){var b=cN||cU(),c=Math.max(0,j.startTime+j.duration-b),d=1-(c/j.duration||0),e=0,f=j.tweens.length;for(;e<f;e++)j.tweens[e].run(d);return h.notifyWith(a,[j,d,c]),d<1&&f?c:(h.resolveWith(a,[j]),!1)},j=h.promise({elem:a,props:p.extend({},b),opts:p.extend(!0,{specialEasing:{}},c),originalProperties:b,originalOptions:c,startTime:cN||cU(),duration:c.duration,tweens:[],createTween:function(b,c,d){var e=p.Tween(a,j.opts,b,c,j.opts.specialEasing[b]||j.opts.easing);return j.tweens.push(e),e},stop:function(b){var c=0,d=b?j.tweens.length:0;for(;c<d;c++)j.tweens[c].run(1);return b?h.resolveWith(a,[j,b]):h.rejectWith(a,[j,b]),this}}),k=j.props;cX(k,j.opts.specialEasing);for(;e<g;e++){d=cS[e].call(j,a,k,j.opts);if(d)return d}return cV(j,k),p.isFunction(j.opts.start)&&j.opts.start.call(a,j),p.fx.timer(p.extend(i,{anim:j,queue:j.opts.queue,elem:a})),j.progress(j.opts.progress).done(j.opts.done,j.opts.complete).fail(j.opts.fail).always(j.opts.always)}function cX(a,b){var c,d,e,f,g;for(c in a){d=p.camelCase(c),e=b[d],f=a[c],p.isArray(f)&&(e=f[1],f=a[c]=f[0]),c!==d&&(a[d]=f,delete a[c]),g=p.cssHooks[d];if(g&&"expand"in g){f=g.expand(f),delete a[d];for(c in f)c in a||(a[c]=f[c],b[c]=e)}else b[d]=e}}function cY(a,b,c){var d,e,f,g,h,i,j,k,l=this,m=a.style,n={},o=[],q=a.nodeType&&bZ(a);c.queue||(j=p._queueHooks(a,"fx"),j.unqueued==null&&(j.unqueued=0,k=j.empty.fire,j.empty.fire=function(){j.unqueued||k()}),j.unqueued++,l.always(function(){l.always(function(){j.unqueued--,p.queue(a,"fx").length||j.empty.fire()})})),a.nodeType===1&&("height"in b||"width"in b)&&(c.overflow=[m.overflow,m.overflowX,m.overflowY],p.css(a,"display")==="inline"&&p.css(a,"float")==="none"&&(!p.support.inlineBlockNeedsLayout||cc(a.nodeName)==="inline"?m.display="inline-block":m.zoom=1)),c.overflow&&(m.overflow="hidden",p.support.shrinkWrapBlocks||l.done(function(){m.overflow=c.overflow[0],m.overflowX=c.overflow[1],m.overflowY=c.overflow[2]}));for(d in b){f=b[d];if(cP.exec(f)){delete b[d];if(f===(q?"hide":"show"))continue;o.push(d)}}g=o.length;if(g){h=p._data(a,"fxshow")||p._data(a,"fxshow",{}),q?p(a).show():l.done(function(){p(a).hide()}),l.done(function(){var b;p.removeData(a,"fxshow",!0);for(b in n)p.style(a,b,n[b])});for(d=0;d<g;d++)e=o[d],i=l.createTween(e,q?h[e]:0),n[e]=h[e]||p.style(a,e),e in h||(h[e]=i.start,q&&(i.end=i.start,i.start=e==="width"||e==="height"?1:0))}}function cZ(a,b,c,d,e){return new cZ.prototype.init(a,b,c,d,e)}function c$(a,b){var c,d={height:a},e=0;b=b?1:0;for(;e<4;e+=2-b)c=bV[e],d["margin"+c]=d["padding"+c]=a;return b&&(d.opacity=d.width=a),d}function da(a){return p.isWindow(a)?a:a.nodeType===9?a.defaultView||a.parentWindow:!1}var c,d,e=a.document,f=a.location,g=a.navigator,h=a.jQuery,i=a.$,j=Array.prototype.push,k=Array.prototype.slice,l=Array.prototype.indexOf,m=Object.prototype.toString,n=Object.prototype.hasOwnProperty,o=String.prototype.trim,p=function(a,b){return new p.fn.init(a,b,c)},q=/[\-+]?(?:\d*\.|)\d+(?:[eE][\-+]?\d+|)/.source,r=/\S/,s=/\s+/,t=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,u=/^(?:[^#<]*(<[\w\W]+>)[^>]*$|#([\w\-]*)$)/,v=/^<(\w+)\s*\/?>(?:<\/\1>|)$/,w=/^[\],:{}\s]*$/,x=/(?:^|:|,)(?:\s*\[)+/g,y=/\\(?:["\\\/bfnrt]|u[\da-fA-F]{4})/g,z=/"[^"\\\r\n]*"|true|false|null|-?(?:\d\d*\.|)\d+(?:[eE][\-+]?\d+|)/g,A=/^-ms-/,B=/-([\da-z])/gi,C=function(a,b){return(b+"").toUpperCase()},D=function(){e.addEventListener?(e.removeEventListener("DOMContentLoaded",D,!1),p.ready()):e.readyState==="complete"&&(e.detachEvent("onreadystatechange",D),p.ready())},E={};p.fn=p.prototype={constructor:p,init:function(a,c,d){var f,g,h,i;if(!a)return this;if(a.nodeType)return this.context=this[0]=a,this.length=1,this;if(typeof a=="string"){a.charAt(0)==="<"&&a.charAt(a.length-1)===">"&&a.length>=3?f=[null,a,null]:f=u.exec(a);if(f&&(f[1]||!c)){if(f[1])return c=c instanceof p?c[0]:c,i=c&&c.nodeType?c.ownerDocument||c:e,a=p.parseHTML(f[1],i,!0),v.test(f[1])&&p.isPlainObject(c)&&this.attr.call(a,c,!0),p.merge(this,a);g=e.getElementById(f[2]);if(g&&g.parentNode){if(g.id!==f[2])return d.find(a);this.length=1,this[0]=g}return this.context=e,this.selector=a,this}return!c||c.jquery?(c||d).find(a):this.constructor(c).find(a)}return p.isFunction(a)?d.ready(a):(a.selector!==b&&(this.selector=a.selector,this.context=a.context),p.makeArray(a,this))},selector:"",jquery:"1.8.2",length:0,size:function(){return this.length},toArray:function(){return k.call(this)},get:function(a){return a==null?this.toArray():a<0?this[this.length+a]:this[a]},pushStack:function(a,b,c){var d=p.merge(this.constructor(),a);return d.prevObject=this,d.context=this.context,b==="find"?d.selector=this.selector+(this.selector?" ":"")+c:b&&(d.selector=this.selector+"."+b+"("+c+")"),d},each:function(a,b){return p.each(this,a,b)},ready:function(a){return p.ready.promise().done(a),this},eq:function(a){return a=+a,a===-1?this.slice(a):this.slice(a,a+1)},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},slice:function(){return this.pushStack(k.apply(this,arguments),"slice",k.call(arguments).join(","))},map:function(a){return this.pushStack(p.map(this,function(b,c){return a.call(b,c,b)}))},end:function(){return this.prevObject||this.constructor(null)},push:j,sort:[].sort,splice:[].splice},p.fn.init.prototype=p.fn,p.extend=p.fn.extend=function(){var a,c,d,e,f,g,h=arguments[0]||{},i=1,j=arguments.length,k=!1;typeof h=="boolean"&&(k=h,h=arguments[1]||{},i=2),typeof h!="object"&&!p.isFunction(h)&&(h={}),j===i&&(h=this,--i);for(;i<j;i++)if((a=arguments[i])!=null)for(c in a){d=h[c],e=a[c];if(h===e)continue;k&&e&&(p.isPlainObject(e)||(f=p.isArray(e)))?(f?(f=!1,g=d&&p.isArray(d)?d:[]):g=d&&p.isPlainObject(d)?d:{},h[c]=p.extend(k,g,e)):e!==b&&(h[c]=e)}return h},p.extend({noConflict:function(b){return a.$===p&&(a.$=i),b&&a.jQuery===p&&(a.jQuery=h),p},isReady:!1,readyWait:1,holdReady:function(a){a?p.readyWait++:p.ready(!0)},ready:function(a){if(a===!0?--p.readyWait:p.isReady)return;if(!e.body)return setTimeout(p.ready,1);p.isReady=!0;if(a!==!0&&--p.readyWait>0)return;d.resolveWith(e,[p]),p.fn.trigger&&p(e).trigger("ready").off("ready")},isFunction:function(a){return p.type(a)==="function"},isArray:Array.isArray||function(a){return p.type(a)==="array"},isWindow:function(a){return a!=null&&a==a.window},isNumeric:function(a){return!isNaN(parseFloat(a))&&isFinite(a)},type:function(a){return a==null?String(a):E[m.call(a)]||"object"},isPlainObject:function(a){if(!a||p.type(a)!=="object"||a.nodeType||p.isWindow(a))return!1;try{if(a.constructor&&!n.call(a,"constructor")&&!n.call(a.constructor.prototype,"isPrototypeOf"))return!1}catch(c){return!1}var d;for(d in a);return d===b||n.call(a,d)},isEmptyObject:function(a){var b;for(b in a)return!1;return!0},error:function(a){throw new Error(a)},parseHTML:function(a,b,c){var d;return!a||typeof a!="string"?null:(typeof b=="boolean"&&(c=b,b=0),b=b||e,(d=v.exec(a))?[b.createElement(d[1])]:(d=p.buildFragment([a],b,c?null:[]),p.merge([],(d.cacheable?p.clone(d.fragment):d.fragment).childNodes)))},parseJSON:function(b){if(!b||typeof b!="string")return null;b=p.trim(b);if(a.JSON&&a.JSON.parse)return a.JSON.parse(b);if(w.test(b.replace(y,"@").replace(z,"]").replace(x,"")))return(new Function("return "+b))();p.error("Invalid JSON: "+b)},parseXML:function(c){var d,e;if(!c||typeof c!="string")return null;try{a.DOMParser?(e=new DOMParser,d=e.parseFromString(c,"text/xml")):(d=new ActiveXObject("Microsoft.XMLDOM"),d.async="false",d.loadXML(c))}catch(f){d=b}return(!d||!d.documentElement||d.getElementsByTagName("parsererror").length)&&p.error("Invalid XML: "+c),d},noop:function(){},globalEval:function(b){b&&r.test(b)&&(a.execScript||function(b){a.eval.call(a,b)})(b)},camelCase:function(a){return a.replace(A,"ms-").replace(B,C)},nodeName:function(a,b){return a.nodeName&&a.nodeName.toLowerCase()===b.toLowerCase()},each:function(a,c,d){var e,f=0,g=a.length,h=g===b||p.isFunction(a);if(d){if(h){for(e in a)if(c.apply(a[e],d)===!1)break}else for(;f<g;)if(c.apply(a[f++],d)===!1)break}else if(h){for(e in a)if(c.call(a[e],e,a[e])===!1)break}else for(;f<g;)if(c.call(a[f],f,a[f++])===!1)break;return a},trim:o&&!o.call("﻿ ")?function(a){return a==null?"":o.call(a)}:function(a){return a==null?"":(a+"").replace(t,"")},makeArray:function(a,b){var c,d=b||[];return a!=null&&(c=p.type(a),a.length==null||c==="string"||c==="function"||c==="regexp"||p.isWindow(a)?j.call(d,a):p.merge(d,a)),d},inArray:function(a,b,c){var d;if(b){if(l)return l.call(b,a,c);d=b.length,c=c?c<0?Math.max(0,d+c):c:0;for(;c<d;c++)if(c in b&&b[c]===a)return c}return-1},merge:function(a,c){var d=c.length,e=a.length,f=0;if(typeof d=="number")for(;f<d;f++)a[e++]=c[f];else while(c[f]!==b)a[e++]=c[f++];return a.length=e,a},grep:function(a,b,c){var d,e=[],f=0,g=a.length;c=!!c;for(;f<g;f++)d=!!b(a[f],f),c!==d&&e.push(a[f]);return e},map:function(a,c,d){var e,f,g=[],h=0,i=a.length,j=a instanceof p||i!==b&&typeof i=="number"&&(i>0&&a[0]&&a[i-1]||i===0||p.isArray(a));if(j)for(;h<i;h++)e=c(a[h],h,d),e!=null&&(g[g.length]=e);else for(f in a)e=c(a[f],f,d),e!=null&&(g[g.length]=e);return g.concat.apply([],g)},guid:1,proxy:function(a,c){var d,e,f;return typeof c=="string"&&(d=a[c],c=a,a=d),p.isFunction(a)?(e=k.call(arguments,2),f=function(){return a.apply(c,e.concat(k.call(arguments)))},f.guid=a.guid=a.guid||p.guid++,f):b},access:function(a,c,d,e,f,g,h){var i,j=d==null,k=0,l=a.length;if(d&&typeof d=="object"){for(k in d)p.access(a,c,k,d[k],1,g,e);f=1}else if(e!==b){i=h===b&&p.isFunction(e),j&&(i?(i=c,c=function(a,b,c){return i.call(p(a),c)}):(c.call(a,e),c=null));if(c)for(;k<l;k++)c(a[k],d,i?e.call(a[k],k,c(a[k],d)):e,h);f=1}return f?a:j?c.call(a):l?c(a[0],d):g},now:function(){return(new Date).getTime()}}),p.ready.promise=function(b){if(!d){d=p.Deferred();if(e.readyState==="complete")setTimeout(p.ready,1);else if(e.addEventListener)e.addEventListener("DOMContentLoaded",D,!1),a.addEventListener("load",p.ready,!1);else{e.attachEvent("onreadystatechange",D),a.attachEvent("onload",p.ready);var c=!1;try{c=a.frameElement==null&&e.documentElement}catch(f){}c&&c.doScroll&&function g(){if(!p.isReady){try{c.doScroll("left")}catch(a){return setTimeout(g,50)}p.ready()}}()}}return d.promise(b)},p.each("Boolean Number String Function Array Date RegExp Object".split(" "),function(a,b){E["[object "+b+"]"]=b.toLowerCase()}),c=p(e);var F={};p.Callbacks=function(a){a=typeof a=="string"?F[a]||G(a):p.extend({},a);var c,d,e,f,g,h,i=[],j=!a.once&&[],k=function(b){c=a.memory&&b,d=!0,h=f||0,f=0,g=i.length,e=!0;for(;i&&h<g;h++)if(i[h].apply(b[0],b[1])===!1&&a.stopOnFalse){c=!1;break}e=!1,i&&(j?j.length&&k(j.shift()):c?i=[]:l.disable())},l={add:function(){if(i){var b=i.length;(function d(b){p.each(b,function(b,c){var e=p.type(c);e==="function"&&(!a.unique||!l.has(c))?i.push(c):c&&c.length&&e!=="string"&&d(c)})})(arguments),e?g=i.length:c&&(f=b,k(c))}return this},remove:function(){return i&&p.each(arguments,function(a,b){var c;while((c=p.inArray(b,i,c))>-1)i.splice(c,1),e&&(c<=g&&g--,c<=h&&h--)}),this},has:function(a){return p.inArray(a,i)>-1},empty:function(){return i=[],this},disable:function(){return i=j=c=b,this},disabled:function(){return!i},lock:function(){return j=b,c||l.disable(),this},locked:function(){return!j},fireWith:function(a,b){return b=b||[],b=[a,b.slice?b.slice():b],i&&(!d||j)&&(e?j.push(b):k(b)),this},fire:function(){return l.fireWith(this,arguments),this},fired:function(){return!!d}};return l},p.extend({Deferred:function(a){var b=[["resolve","done",p.Callbacks("once memory"),"resolved"],["reject","fail",p.Callbacks("once memory"),"rejected"],["notify","progress",p.Callbacks("memory")]],c="pending",d={state:function(){return c},always:function(){return e.done(arguments).fail(arguments),this},then:function(){var a=arguments;return p.Deferred(function(c){p.each(b,function(b,d){var f=d[0],g=a[b];e[d[1]](p.isFunction(g)?function(){var a=g.apply(this,arguments);a&&p.isFunction(a.promise)?a.promise().done(c.resolve).fail(c.reject).progress(c.notify):c[f+"With"](this===e?c:this,[a])}:c[f])}),a=null}).promise()},promise:function(a){return a!=null?p.extend(a,d):d}},e={};return d.pipe=d.then,p.each(b,function(a,f){var g=f[2],h=f[3];d[f[1]]=g.add,h&&g.add(function(){c=h},b[a^1][2].disable,b[2][2].lock),e[f[0]]=g.fire,e[f[0]+"With"]=g.fireWith}),d.promise(e),a&&a.call(e,e),e},when:function(a){var b=0,c=k.call(arguments),d=c.length,e=d!==1||a&&p.isFunction(a.promise)?d:0,f=e===1?a:p.Deferred(),g=function(a,b,c){return function(d){b[a]=this,c[a]=arguments.length>1?k.call(arguments):d,c===h?f.notifyWith(b,c):--e||f.resolveWith(b,c)}},h,i,j;if(d>1){h=new Array(d),i=new Array(d),j=new Array(d);for(;b<d;b++)c[b]&&p.isFunction(c[b].promise)?c[b].promise().done(g(b,j,c)).fail(f.reject).progress(g(b,i,h)):--e}return e||f.resolveWith(j,c),f.promise()}}),p.support=function(){var b,c,d,f,g,h,i,j,k,l,m,n=e.createElement("div");n.setAttribute("className","t"),n.innerHTML="  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",c=n.getElementsByTagName("*"),d=n.getElementsByTagName("a")[0],d.style.cssText="top:1px;float:left;opacity:.5";if(!c||!c.length)return{};f=e.createElement("select"),g=f.appendChild(e.createElement("option")),h=n.getElementsByTagName("input")[0],b={leadingWhitespace:n.firstChild.nodeType===3,tbody:!n.getElementsByTagName("tbody").length,htmlSerialize:!!n.getElementsByTagName("link").length,style:/top/.test(d.getAttribute("style")),hrefNormalized:d.getAttribute("href")==="/a",opacity:/^0.5/.test(d.style.opacity),cssFloat:!!d.style.cssFloat,checkOn:h.value==="on",optSelected:g.selected,getSetAttribute:n.className!=="t",enctype:!!e.createElement("form").enctype,html5Clone:e.createElement("nav").cloneNode(!0).outerHTML!=="<:nav></:nav>",boxModel:e.compatMode==="CSS1Compat",submitBubbles:!0,changeBubbles:!0,focusinBubbles:!1,deleteExpando:!0,noCloneEvent:!0,inlineBlockNeedsLayout:!1,shrinkWrapBlocks:!1,reliableMarginRight:!0,boxSizingReliable:!0,pixelPosition:!1},h.checked=!0,b.noCloneChecked=h.cloneNode(!0).checked,f.disabled=!0,b.optDisabled=!g.disabled;try{delete n.test}catch(o){b.deleteExpando=!1}!n.addEventListener&&n.attachEvent&&n.fireEvent&&(n.attachEvent("onclick",m=function(){b.noCloneEvent=!1}),n.cloneNode(!0).fireEvent("onclick"),n.detachEvent("onclick",m)),h=e.createElement("input"),h.value="t",h.setAttribute("type","radio"),b.radioValue=h.value==="t",h.setAttribute("checked","checked"),h.setAttribute("name","t"),n.appendChild(h),i=e.createDocumentFragment(),i.appendChild(n.lastChild),b.checkClone=i.cloneNode(!0).cloneNode(!0).lastChild.checked,b.appendChecked=h.checked,i.removeChild(h),i.appendChild(n);if(n.attachEvent)for(k in{submit:!0,change:!0,focusin:!0})j="on"+k,l=j in n,l||(n.setAttribute(j,"return;"),l=typeof n[j]=="function"),b[k+"Bubbles"]=l;return p(function(){var c,d,f,g,h="padding:0;margin:0;border:0;display:block;overflow:hidden;",i=e.getElementsByTagName("body")[0];if(!i)return;c=e.createElement("div"),c.style.cssText="visibility:hidden;border:0;width:0;height:0;position:static;top:0;margin-top:1px",i.insertBefore(c,i.firstChild),d=e.createElement("div"),c.appendChild(d),d.innerHTML="<table><tr><td></td><td>t</td></tr></table>",f=d.getElementsByTagName("td"),f[0].style.cssText="padding:0;margin:0;border:0;display:none",l=f[0].offsetHeight===0,f[0].style.display="",f[1].style.display="none",b.reliableHiddenOffsets=l&&f[0].offsetHeight===0,d.innerHTML="",d.style.cssText="box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%;",b.boxSizing=d.offsetWidth===4,b.doesNotIncludeMarginInBodyOffset=i.offsetTop!==1,a.getComputedStyle&&(b.pixelPosition=(a.getComputedStyle(d,null)||{}).top!=="1%",b.boxSizingReliable=(a.getComputedStyle(d,null)||{width:"4px"}).width==="4px",g=e.createElement("div"),g.style.cssText=d.style.cssText=h,g.style.marginRight=g.style.width="0",d.style.width="1px",d.appendChild(g),b.reliableMarginRight=!parseFloat((a.getComputedStyle(g,null)||{}).marginRight)),typeof d.style.zoom!="undefined"&&(d.innerHTML="",d.style.cssText=h+"width:1px;padding:1px;display:inline;zoom:1",b.inlineBlockNeedsLayout=d.offsetWidth===3,d.style.display="block",d.style.overflow="visible",d.innerHTML="<div></div>",d.firstChild.style.width="5px",b.shrinkWrapBlocks=d.offsetWidth!==3,c.style.zoom=1),i.removeChild(c),c=d=f=g=null}),i.removeChild(n),c=d=f=g=h=i=n=null,b}();var H=/(?:\{[\s\S]*\}|\[[\s\S]*\])$/,I=/([A-Z])/g;p.extend({cache:{},deletedIds:[],uuid:0,expando:"jQuery"+(p.fn.jquery+Math.random()).replace(/\D/g,""),noData:{embed:!0,object:"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",applet:!0},hasData:function(a){return a=a.nodeType?p.cache[a[p.expando]]:a[p.expando],!!a&&!K(a)},data:function(a,c,d,e){if(!p.acceptData(a))return;var f,g,h=p.expando,i=typeof c=="string",j=a.nodeType,k=j?p.cache:a,l=j?a[h]:a[h]&&h;if((!l||!k[l]||!e&&!k[l].data)&&i&&d===b)return;l||(j?a[h]=l=p.deletedIds.pop()||p.guid++:l=h),k[l]||(k[l]={},j||(k[l].toJSON=p.noop));if(typeof c=="object"||typeof c=="function")e?k[l]=p.extend(k[l],c):k[l].data=p.extend(k[l].data,c);return f=k[l],e||(f.data||(f.data={}),f=f.data),d!==b&&(f[p.camelCase(c)]=d),i?(g=f[c],g==null&&(g=f[p.camelCase(c)])):g=f,g},removeData:function(a,b,c){if(!p.acceptData(a))return;var d,e,f,g=a.nodeType,h=g?p.cache:a,i=g?a[p.expando]:p.expando;if(!h[i])return;if(b){d=c?h[i]:h[i].data;if(d){p.isArray(b)||(b in d?b=[b]:(b=p.camelCase(b),b in d?b=[b]:b=b.split(" ")));for(e=0,f=b.length;e<f;e++)delete d[b[e]];if(!(c?K:p.isEmptyObject)(d))return}}if(!c){delete h[i].data;if(!K(h[i]))return}g?p.cleanData([a],!0):p.support.deleteExpando||h!=h.window?delete h[i]:h[i]=null},_data:function(a,b,c){return p.data(a,b,c,!0)},acceptData:function(a){var b=a.nodeName&&p.noData[a.nodeName.toLowerCase()];return!b||b!==!0&&a.getAttribute("classid")===b}}),p.fn.extend({data:function(a,c){var d,e,f,g,h,i=this[0],j=0,k=null;if(a===b){if(this.length){k=p.data(i);if(i.nodeType===1&&!p._data(i,"parsedAttrs")){f=i.attributes;for(h=f.length;j<h;j++)g=f[j].name,g.indexOf("data-")||(g=p.camelCase(g.substring(5)),J(i,g,k[g]));p._data(i,"parsedAttrs",!0)}}return k}return typeof a=="object"?this.each(function(){p.data(this,a)}):(d=a.split(".",2),d[1]=d[1]?"."+d[1]:"",e=d[1]+"!",p.access(this,function(c){if(c===b)return k=this.triggerHandler("getData"+e,[d[0]]),k===b&&i&&(k=p.data(i,a),k=J(i,a,k)),k===b&&d[1]?this.data(d[0]):k;d[1]=c,this.each(function(){var b=p(this);b.triggerHandler("setData"+e,d),p.data(this,a,c),b.triggerHandler("changeData"+e,d)})},null,c,arguments.length>1,null,!1))},removeData:function(a){return this.each(function(){p.removeData(this,a)})}}),p.extend({queue:function(a,b,c){var d;if(a)return b=(b||"fx")+"queue",d=p._data(a,b),c&&(!d||p.isArray(c)?d=p._data(a,b,p.makeArray(c)):d.push(c)),d||[]},dequeue:function(a,b){b=b||"fx";var c=p.queue(a,b),d=c.length,e=c.shift(),f=p._queueHooks(a,b),g=function(){p.dequeue(a,b)};e==="inprogress"&&(e=c.shift(),d--),e&&(b==="fx"&&c.unshift("inprogress"),delete f.stop,e.call(a,g,f)),!d&&f&&f.empty.fire()},_queueHooks:function(a,b){var c=b+"queueHooks";return p._data(a,c)||p._data(a,c,{empty:p.Callbacks("once memory").add(function(){p.removeData(a,b+"queue",!0),p.removeData(a,c,!0)})})}}),p.fn.extend({queue:function(a,c){var d=2;return typeof a!="string"&&(c=a,a="fx",d--),arguments.length<d?p.queue(this[0],a):c===b?this:this.each(function(){var b=p.queue(this,a,c);p._queueHooks(this,a),a==="fx"&&b[0]!=="inprogress"&&p.dequeue(this,a)})},dequeue:function(a){return this.each(function(){p.dequeue(this,a)})},delay:function(a,b){return a=p.fx?p.fx.speeds[a]||a:a,b=b||"fx",this.queue(b,function(b,c){var d=setTimeout(b,a);c.stop=function(){clearTimeout(d)}})},clearQueue:function(a){return this.queue(a||"fx",[])},promise:function(a,c){var d,e=1,f=p.Deferred(),g=this,h=this.length,i=function(){--e||f.resolveWith(g,[g])};typeof a!="string"&&(c=a,a=b),a=a||"fx";while(h--)d=p._data(g[h],a+"queueHooks"),d&&d.empty&&(e++,d.empty.add(i));return i(),f.promise(c)}});var L,M,N,O=/[\t\r\n]/g,P=/\r/g,Q=/^(?:button|input)$/i,R=/^(?:button|input|object|select|textarea)$/i,S=/^a(?:rea|)$/i,T=/^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i,U=p.support.getSetAttribute;p.fn.extend({attr:function(a,b){return p.access(this,p.attr,a,b,arguments.length>1)},removeAttr:function(a){return this.each(function(){p.removeAttr(this,a)})},prop:function(a,b){return p.access(this,p.prop,a,b,arguments.length>1)},removeProp:function(a){return a=p.propFix[a]||a,this.each(function(){try{this[a]=b,delete this[a]}catch(c){}})},addClass:function(a){var b,c,d,e,f,g,h;if(p.isFunction(a))return this.each(function(b){p(this).addClass(a.call(this,b,this.className))});if(a&&typeof a=="string"){b=a.split(s);for(c=0,d=this.length;c<d;c++){e=this[c];if(e.nodeType===1)if(!e.className&&b.length===1)e.className=a;else{f=" "+e.className+" ";for(g=0,h=b.length;g<h;g++)f.indexOf(" "+b[g]+" ")<0&&(f+=b[g]+" ");e.className=p.trim(f)}}}return this},removeClass:function(a){var c,d,e,f,g,h,i;if(p.isFunction(a))return this.each(function(b){p(this).removeClass(a.call(this,b,this.className))});if(a&&typeof a=="string"||a===b){c=(a||"").split(s);for(h=0,i=this.length;h<i;h++){e=this[h];if(e.nodeType===1&&e.className){d=(" "+e.className+" ").replace(O," ");for(f=0,g=c.length;f<g;f++)while(d.indexOf(" "+c[f]+" ")>=0)d=d.replace(" "+c[f]+" "," ");e.className=a?p.trim(d):""}}}return this},toggleClass:function(a,b){var c=typeof a,d=typeof b=="boolean";return p.isFunction(a)?this.each(function(c){p(this).toggleClass(a.call(this,c,this.className,b),b)}):this.each(function(){if(c==="string"){var e,f=0,g=p(this),h=b,i=a.split(s);while(e=i[f++])h=d?h:!g.hasClass(e),g[h?"addClass":"removeClass"](e)}else if(c==="undefined"||c==="boolean")this.className&&p._data(this,"__className__",this.className),this.className=this.className||a===!1?"":p._data(this,"__className__")||""})},hasClass:function(a){var b=" "+a+" ",c=0,d=this.length;for(;c<d;c++)if(this[c].nodeType===1&&(" "+this[c].className+" ").replace(O," ").indexOf(b)>=0)return!0;return!1},val:function(a){var c,d,e,f=this[0];if(!arguments.length){if(f)return c=p.valHooks[f.type]||p.valHooks[f.nodeName.toLowerCase()],c&&"get"in c&&(d=c.get(f,"value"))!==b?d:(d=f.value,typeof d=="string"?d.replace(P,""):d==null?"":d);return}return e=p.isFunction(a),this.each(function(d){var f,g=p(this);if(this.nodeType!==1)return;e?f=a.call(this,d,g.val()):f=a,f==null?f="":typeof f=="number"?f+="":p.isArray(f)&&(f=p.map(f,function(a){return a==null?"":a+""})),c=p.valHooks[this.type]||p.valHooks[this.nodeName.toLowerCase()];if(!c||!("set"in c)||c.set(this,f,"value")===b)this.value=f})}}),p.extend({valHooks:{option:{get:function(a){var b=a.attributes.value;return!b||b.specified?a.value:a.text}},select:{get:function(a){var b,c,d,e,f=a.selectedIndex,g=[],h=a.options,i=a.type==="select-one";if(f<0)return null;c=i?f:0,d=i?f+1:h.length;for(;c<d;c++){e=h[c];if(e.selected&&(p.support.optDisabled?!e.disabled:e.getAttribute("disabled")===null)&&(!e.parentNode.disabled||!p.nodeName(e.parentNode,"optgroup"))){b=p(e).val();if(i)return b;g.push(b)}}return i&&!g.length&&h.length?p(h[f]).val():g},set:function(a,b){var c=p.makeArray(b);return p(a).find("option").each(function(){this.selected=p.inArray(p(this).val(),c)>=0}),c.length||(a.selectedIndex=-1),c}}},attrFn:{},attr:function(a,c,d,e){var f,g,h,i=a.nodeType;if(!a||i===3||i===8||i===2)return;if(e&&p.isFunction(p.fn[c]))return p(a)[c](d);if(typeof a.getAttribute=="undefined")return p.prop(a,c,d);h=i!==1||!p.isXMLDoc(a),h&&(c=c.toLowerCase(),g=p.attrHooks[c]||(T.test(c)?M:L));if(d!==b){if(d===null){p.removeAttr(a,c);return}return g&&"set"in g&&h&&(f=g.set(a,d,c))!==b?f:(a.setAttribute(c,d+""),d)}return g&&"get"in g&&h&&(f=g.get(a,c))!==null?f:(f=a.getAttribute(c),f===null?b:f)},removeAttr:function(a,b){var c,d,e,f,g=0;if(b&&a.nodeType===1){d=b.split(s);for(;g<d.length;g++)e=d[g],e&&(c=p.propFix[e]||e,f=T.test(e),f||p.attr(a,e,""),a.removeAttribute(U?e:c),f&&c in a&&(a[c]=!1))}},attrHooks:{type:{set:function(a,b){if(Q.test(a.nodeName)&&a.parentNode)p.error("type property can't be changed");else if(!p.support.radioValue&&b==="radio"&&p.nodeName(a,"input")){var c=a.value;return a.setAttribute("type",b),c&&(a.value=c),b}}},value:{get:function(a,b){return L&&p.nodeName(a,"button")?L.get(a,b):b in a?a.value:null},set:function(a,b,c){if(L&&p.nodeName(a,"button"))return L.set(a,b,c);a.value=b}}},propFix:{tabindex:"tabIndex",readonly:"readOnly","for":"htmlFor","class":"className",maxlength:"maxLength",cellspacing:"cellSpacing",cellpadding:"cellPadding",rowspan:"rowSpan",colspan:"colSpan",usemap:"useMap",frameborder:"frameBorder",contenteditable:"contentEditable"},prop:function(a,c,d){var e,f,g,h=a.nodeType;if(!a||h===3||h===8||h===2)return;return g=h!==1||!p.isXMLDoc(a),g&&(c=p.propFix[c]||c,f=p.propHooks[c]),d!==b?f&&"set"in f&&(e=f.set(a,d,c))!==b?e:a[c]=d:f&&"get"in f&&(e=f.get(a,c))!==null?e:a[c]},propHooks:{tabIndex:{get:function(a){var c=a.getAttributeNode("tabindex");return c&&c.specified?parseInt(c.value,10):R.test(a.nodeName)||S.test(a.nodeName)&&a.href?0:b}}}}),M={get:function(a,c){var d,e=p.prop(a,c);return e===!0||typeof e!="boolean"&&(d=a.getAttributeNode(c))&&d.nodeValue!==!1?c.toLowerCase():b},set:function(a,b,c){var d;return b===!1?p.removeAttr(a,c):(d=p.propFix[c]||c,d in a&&(a[d]=!0),a.setAttribute(c,c.toLowerCase())),c}},U||(N={name:!0,id:!0,coords:!0},L=p.valHooks.button={get:function(a,c){var d;return d=a.getAttributeNode(c),d&&(N[c]?d.value!=="":d.specified)?d.value:b},set:function(a,b,c){var d=a.getAttributeNode(c);return d||(d=e.createAttribute(c),a.setAttributeNode(d)),d.value=b+""}},p.each(["width","height"],function(a,b){p.attrHooks[b]=p.extend(p.attrHooks[b],{set:function(a,c){if(c==="")return a.setAttribute(b,"auto"),c}})}),p.attrHooks.contenteditable={get:L.get,set:function(a,b,c){b===""&&(b="false"),L.set(a,b,c)}}),p.support.hrefNormalized||p.each(["href","src","width","height"],function(a,c){p.attrHooks[c]=p.extend(p.attrHooks[c],{get:function(a){var d=a.getAttribute(c,2);return d===null?b:d}})}),p.support.style||(p.attrHooks.style={get:function(a){return a.style.cssText.toLowerCase()||b},set:function(a,b){return a.style.cssText=b+""}}),p.support.optSelected||(p.propHooks.selected=p.extend(p.propHooks.selected,{get:function(a){var b=a.parentNode;return b&&(b.selectedIndex,b.parentNode&&b.parentNode.selectedIndex),null}})),p.support.enctype||(p.propFix.enctype="encoding"),p.support.checkOn||p.each(["radio","checkbox"],function(){p.valHooks[this]={get:function(a){return a.getAttribute("value")===null?"on":a.value}}}),p.each(["radio","checkbox"],function(){p.valHooks[this]=p.extend(p.valHooks[this],{set:function(a,b){if(p.isArray(b))return a.checked=p.inArray(p(a).val(),b)>=0}})});var V=/^(?:textarea|input|select)$/i,W=/^([^\.]*|)(?:\.(.+)|)$/,X=/(?:^|\s)hover(\.\S+|)\b/,Y=/^key/,Z=/^(?:mouse|contextmenu)|click/,$=/^(?:focusinfocus|focusoutblur)$/,_=function(a){return p.event.special.hover?a:a.replace(X,"mouseenter$1 mouseleave$1")};p.event={add:function(a,c,d,e,f){var g,h,i,j,k,l,m,n,o,q,r;if(a.nodeType===3||a.nodeType===8||!c||!d||!(g=p._data(a)))return;d.handler&&(o=d,d=o.handler,f=o.selector),d.guid||(d.guid=p.guid++),i=g.events,i||(g.events=i={}),h=g.handle,h||(g.handle=h=function(a){return typeof p!="undefined"&&(!a||p.event.triggered!==a.type)?p.event.dispatch.apply(h.elem,arguments):b},h.elem=a),c=p.trim(_(c)).split(" ");for(j=0;j<c.length;j++){k=W.exec(c[j])||[],l=k[1],m=(k[2]||"").split(".").sort(),r=p.event.special[l]||{},l=(f?r.delegateType:r.bindType)||l,r=p.event.special[l]||{},n=p.extend({type:l,origType:k[1],data:e,handler:d,guid:d.guid,selector:f,needsContext:f&&p.expr.match.needsContext.test(f),namespace:m.join(".")},o),q=i[l];if(!q){q=i[l]=[],q.delegateCount=0;if(!r.setup||r.setup.call(a,e,m,h)===!1)a.addEventListener?a.addEventListener(l,h,!1):a.attachEvent&&a.attachEvent("on"+l,h)}r.add&&(r.add.call(a,n),n.handler.guid||(n.handler.guid=d.guid)),f?q.splice(q.delegateCount++,0,n):q.push(n),p.event.global[l]=!0}a=null},global:{},remove:function(a,b,c,d,e){var f,g,h,i,j,k,l,m,n,o,q,r=p.hasData(a)&&p._data(a);if(!r||!(m=r.events))return;b=p.trim(_(b||"")).split(" ");for(f=0;f<b.length;f++){g=W.exec(b[f])||[],h=i=g[1],j=g[2];if(!h){for(h in m)p.event.remove(a,h+b[f],c,d,!0);continue}n=p.event.special[h]||{},h=(d?n.delegateType:n.bindType)||h,o=m[h]||[],k=o.length,j=j?new RegExp("(^|\\.)"+j.split(".").sort().join("\\.(?:.*\\.|)")+"(\\.|$)"):null;for(l=0;l<o.length;l++)q=o[l],(e||i===q.origType)&&(!c||c.guid===q.guid)&&(!j||j.test(q.namespace))&&(!d||d===q.selector||d==="**"&&q.selector)&&(o.splice(l--,1),q.selector&&o.delegateCount--,n.remove&&n.remove.call(a,q));o.length===0&&k!==o.length&&((!n.teardown||n.teardown.call(a,j,r.handle)===!1)&&p.removeEvent(a,h,r.handle),delete m[h])}p.isEmptyObject(m)&&(delete r.handle,p.removeData(a,"events",!0))},customEvent:{getData:!0,setData:!0,changeData:!0},trigger:function(c,d,f,g){if(!f||f.nodeType!==3&&f.nodeType!==8){var h,i,j,k,l,m,n,o,q,r,s=c.type||c,t=[];if($.test(s+p.event.triggered))return;s.indexOf("!")>=0&&(s=s.slice(0,-1),i=!0),s.indexOf(".")>=0&&(t=s.split("."),s=t.shift(),t.sort());if((!f||p.event.customEvent[s])&&!p.event.global[s])return;c=typeof c=="object"?c[p.expando]?c:new p.Event(s,c):new p.Event(s),c.type=s,c.isTrigger=!0,c.exclusive=i,c.namespace=t.join("."),c.namespace_re=c.namespace?new RegExp("(^|\\.)"+t.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,m=s.indexOf(":")<0?"on"+s:"";if(!f){h=p.cache;for(j in h)h[j].events&&h[j].events[s]&&p.event.trigger(c,d,h[j].handle.elem,!0);return}c.result=b,c.target||(c.target=f),d=d!=null?p.makeArray(d):[],d.unshift(c),n=p.event.special[s]||{};if(n.trigger&&n.trigger.apply(f,d)===!1)return;q=[[f,n.bindType||s]];if(!g&&!n.noBubble&&!p.isWindow(f)){r=n.delegateType||s,k=$.test(r+s)?f:f.parentNode;for(l=f;k;k=k.parentNode)q.push([k,r]),l=k;l===(f.ownerDocument||e)&&q.push([l.defaultView||l.parentWindow||a,r])}for(j=0;j<q.length&&!c.isPropagationStopped();j++)k=q[j][0],c.type=q[j][1],o=(p._data(k,"events")||{})[c.type]&&p._data(k,"handle"),o&&o.apply(k,d),o=m&&k[m],o&&p.acceptData(k)&&o.apply&&o.apply(k,d)===!1&&c.preventDefault();return c.type=s,!g&&!c.isDefaultPrevented()&&(!n._default||n._default.apply(f.ownerDocument,d)===!1)&&(s!=="click"||!p.nodeName(f,"a"))&&p.acceptData(f)&&m&&f[s]&&(s!=="focus"&&s!=="blur"||c.target.offsetWidth!==0)&&!p.isWindow(f)&&(l=f[m],l&&(f[m]=null),p.event.triggered=s,f[s](),p.event.triggered=b,l&&(f[m]=l)),c.result}return},dispatch:function(c){c=p.event.fix(c||a.event);var d,e,f,g,h,i,j,l,m,n,o=(p._data(this,"events")||{})[c.type]||[],q=o.delegateCount,r=k.call(arguments),s=!c.exclusive&&!c.namespace,t=p.event.special[c.type]||{},u=[];r[0]=c,c.delegateTarget=this;if(t.preDispatch&&t.preDispatch.call(this,c)===!1)return;if(q&&(!c.button||c.type!=="click"))for(f=c.target;f!=this;f=f.parentNode||this)if(f.disabled!==!0||c.type!=="click"){h={},j=[];for(d=0;d<q;d++)l=o[d],m=l.selector,h[m]===b&&(h[m]=l.needsContext?p(m,this).index(f)>=0:p.find(m,this,null,[f]).length),h[m]&&j.push(l);j.length&&u.push({elem:f,matches:j})}o.length>q&&u.push({elem:this,matches:o.slice(q)});for(d=0;d<u.length&&!c.isPropagationStopped();d++){i=u[d],c.currentTarget=i.elem;for(e=0;e<i.matches.length&&!c.isImmediatePropagationStopped();e++){l=i.matches[e];if(s||!c.namespace&&!l.namespace||c.namespace_re&&c.namespace_re.test(l.namespace))c.data=l.data,c.handleObj=l,g=((p.event.special[l.origType]||{}).handle||l.handler).apply(i.elem,r),g!==b&&(c.result=g,g===!1&&(c.preventDefault(),c.stopPropagation()))}}return t.postDispatch&&t.postDispatch.call(this,c),c.result},props:"attrChange attrName relatedNode srcElement altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),fixHooks:{},keyHooks:{props:"char charCode key keyCode".split(" "),filter:function(a,b){return a.which==null&&(a.which=b.charCode!=null?b.charCode:b.keyCode),a}},mouseHooks:{props:"button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),filter:function(a,c){var d,f,g,h=c.button,i=c.fromElement;return a.pageX==null&&c.clientX!=null&&(d=a.target.ownerDocument||e,f=d.documentElement,g=d.body,a.pageX=c.clientX+(f&&f.scrollLeft||g&&g.scrollLeft||0)-(f&&f.clientLeft||g&&g.clientLeft||0),a.pageY=c.clientY+(f&&f.scrollTop||g&&g.scrollTop||0)-(f&&f.clientTop||g&&g.clientTop||0)),!a.relatedTarget&&i&&(a.relatedTarget=i===a.target?c.toElement:i),!a.which&&h!==b&&(a.which=h&1?1:h&2?3:h&4?2:0),a}},fix:function(a){if(a[p.expando])return a;var b,c,d=a,f=p.event.fixHooks[a.type]||{},g=f.props?this.props.concat(f.props):this.props;a=p.Event(d);for(b=g.length;b;)c=g[--b],a[c]=d[c];return a.target||(a.target=d.srcElement||e),a.target.nodeType===3&&(a.target=a.target.parentNode),a.metaKey=!!a.metaKey,f.filter?f.filter(a,d):a},special:{load:{noBubble:!0},focus:{delegateType:"focusin"},blur:{delegateType:"focusout"},beforeunload:{setup:function(a,b,c){p.isWindow(this)&&(this.onbeforeunload=c)},teardown:function(a,b){this.onbeforeunload===b&&(this.onbeforeunload=null)}}},simulate:function(a,b,c,d){var e=p.extend(new p.Event,c,{type:a,isSimulated:!0,originalEvent:{}});d?p.event.trigger(e,null,b):p.event.dispatch.call(b,e),e.isDefaultPrevented()&&c.preventDefault()}},p.event.handle=p.event.dispatch,p.removeEvent=e.removeEventListener?function(a,b,c){a.removeEventListener&&a.removeEventListener(b,c,!1)}:function(a,b,c){var d="on"+b;a.detachEvent&&(typeof a[d]=="undefined"&&(a[d]=null),a.detachEvent(d,c))},p.Event=function(a,b){if(this instanceof p.Event)a&&a.type?(this.originalEvent=a,this.type=a.type,this.isDefaultPrevented=a.defaultPrevented||a.returnValue===!1||a.getPreventDefault&&a.getPreventDefault()?bb:ba):this.type=a,b&&p.extend(this,b),this.timeStamp=a&&a.timeStamp||p.now(),this[p.expando]=!0;else return new p.Event(a,b)},p.Event.prototype={preventDefault:function(){this.isDefaultPrevented=bb;var a=this.originalEvent;if(!a)return;a.preventDefault?a.preventDefault():a.returnValue=!1},stopPropagation:function(){this.isPropagationStopped=bb;var a=this.originalEvent;if(!a)return;a.stopPropagation&&a.stopPropagation(),a.cancelBubble=!0},stopImmediatePropagation:function(){this.isImmediatePropagationStopped=bb,this.stopPropagation()},isDefaultPrevented:ba,isPropagationStopped:ba,isImmediatePropagationStopped:ba},p.each({mouseenter:"mouseover",mouseleave:"mouseout"},function(a,b){p.event.special[a]={delegateType:b,bindType:b,handle:function(a){var c,d=this,e=a.relatedTarget,f=a.handleObj,g=f.selector;if(!e||e!==d&&!p.contains(d,e))a.type=f.origType,c=f.handler.apply(this,arguments),a.type=b;return c}}}),p.support.submitBubbles||(p.event.special.submit={setup:function(){if(p.nodeName(this,"form"))return!1;p.event.add(this,"click._submit keypress._submit",function(a){var c=a.target,d=p.nodeName(c,"input")||p.nodeName(c,"button")?c.form:b;d&&!p._data(d,"_submit_attached")&&(p.event.add(d,"submit._submit",function(a){a._submit_bubble=!0}),p._data(d,"_submit_attached",!0))})},postDispatch:function(a){a._submit_bubble&&(delete a._submit_bubble,this.parentNode&&!a.isTrigger&&p.event.simulate("submit",this.parentNode,a,!0))},teardown:function(){if(p.nodeName(this,"form"))return!1;p.event.remove(this,"._submit")}}),p.support.changeBubbles||(p.event.special.change={setup:function(){if(V.test(this.nodeName)){if(this.type==="checkbox"||this.type==="radio")p.event.add(this,"propertychange._change",function(a){a.originalEvent.propertyName==="checked"&&(this._just_changed=!0)}),p.event.add(this,"click._change",function(a){this._just_changed&&!a.isTrigger&&(this._just_changed=!1),p.event.simulate("change",this,a,!0)});return!1}p.event.add(this,"beforeactivate._change",function(a){var b=a.target;V.test(b.nodeName)&&!p._data(b,"_change_attached")&&(p.event.add(b,"change._change",function(a){this.parentNode&&!a.isSimulated&&!a.isTrigger&&p.event.simulate("change",this.parentNode,a,!0)}),p._data(b,"_change_attached",!0))})},handle:function(a){var b=a.target;if(this!==b||a.isSimulated||a.isTrigger||b.type!=="radio"&&b.type!=="checkbox")return a.handleObj.handler.apply(this,arguments)},teardown:function(){return p.event.remove(this,"._change"),!V.test(this.nodeName)}}),p.support.focusinBubbles||p.each({focus:"focusin",blur:"focusout"},function(a,b){var c=0,d=function(a){p.event.simulate(b,a.target,p.event.fix(a),!0)};p.event.special[b]={setup:function(){c++===0&&e.addEventListener(a,d,!0)},teardown:function(){--c===0&&e.removeEventListener(a,d,!0)}}}),p.fn.extend({on:function(a,c,d,e,f){var g,h;if(typeof a=="object"){typeof c!="string"&&(d=d||c,c=b);for(h in a)this.on(h,c,d,a[h],f);return this}d==null&&e==null?(e=c,d=c=b):e==null&&(typeof c=="string"?(e=d,d=b):(e=d,d=c,c=b));if(e===!1)e=ba;else if(!e)return this;return f===1&&(g=e,e=function(a){return p().off(a),g.apply(this,arguments)},e.guid=g.guid||(g.guid=p.guid++)),this.each(function(){p.event.add(this,a,e,d,c)})},one:function(a,b,c,d){return this.on(a,b,c,d,1)},off:function(a,c,d){var e,f;if(a&&a.preventDefault&&a.handleObj)return e=a.handleObj,p(a.delegateTarget).off(e.namespace?e.origType+"."+e.namespace:e.origType,e.selector,e.handler),this;if(typeof a=="object"){for(f in a)this.off(f,c,a[f]);return this}if(c===!1||typeof c=="function")d=c,c=b;return d===!1&&(d=ba),this.each(function(){p.event.remove(this,a,d,c)})},bind:function(a,b,c){return this.on(a,null,b,c)},unbind:function(a,b){return this.off(a,null,b)},live:function(a,b,c){return p(this.context).on(a,this.selector,b,c),this},die:function(a,b){return p(this.context).off(a,this.selector||"**",b),this},delegate:function(a,b,c,d){return this.on(b,a,c,d)},undelegate:function(a,b,c){return arguments.length===1?this.off(a,"**"):this.off(b,a||"**",c)},trigger:function(a,b){return this.each(function(){p.event.trigger(a,b,this)})},triggerHandler:function(a,b){if(this[0])return p.event.trigger(a,b,this[0],!0)},toggle:function(a){var b=arguments,c=a.guid||p.guid++,d=0,e=function(c){var e=(p._data(this,"lastToggle"+a.guid)||0)%d;return p._data(this,"lastToggle"+a.guid,e+1),c.preventDefault(),b[e].apply(this,arguments)||!1};e.guid=c;while(d<b.length)b[d++].guid=c;return this.click(e)},hover:function(a,b){return this.mouseenter(a).mouseleave(b||a)}}),p.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),function(a,b){p.fn[b]=function(a,c){return c==null&&(c=a,a=null),arguments.length>0?this.on(b,null,a,c):this.trigger(b)},Y.test(b)&&(p.event.fixHooks[b]=p.event.keyHooks),Z.test(b)&&(p.event.fixHooks[b]=p.event.mouseHooks)}),function(a,b){function bc(a,b,c,d){c=c||[],b=b||r;var e,f,i,j,k=b.nodeType;if(!a||typeof a!="string")return c;if(k!==1&&k!==9)return[];i=g(b);if(!i&&!d)if(e=P.exec(a))if(j=e[1]){if(k===9){f=b.getElementById(j);if(!f||!f.parentNode)return c;if(f.id===j)return c.push(f),c}else if(b.ownerDocument&&(f=b.ownerDocument.getElementById(j))&&h(b,f)&&f.id===j)return c.push(f),c}else{if(e[2])return w.apply(c,x.call(b.getElementsByTagName(a),0)),c;if((j=e[3])&&_&&b.getElementsByClassName)return w.apply(c,x.call(b.getElementsByClassName(j),0)),c}return bp(a.replace(L,"$1"),b,c,d,i)}function bd(a){return function(b){var c=b.nodeName.toLowerCase();return c==="input"&&b.type===a}}function be(a){return function(b){var c=b.nodeName.toLowerCase();return(c==="input"||c==="button")&&b.type===a}}function bf(a){return z(function(b){return b=+b,z(function(c,d){var e,f=a([],c.length,b),g=f.length;while(g--)c[e=f[g]]&&(c[e]=!(d[e]=c[e]))})})}function bg(a,b,c){if(a===b)return c;var d=a.nextSibling;while(d){if(d===b)return-1;d=d.nextSibling}return 1}function bh(a,b){var c,d,f,g,h,i,j,k=C[o][a];if(k)return b?0:k.slice(0);h=a,i=[],j=e.preFilter;while(h){if(!c||(d=M.exec(h)))d&&(h=h.slice(d[0].length)),i.push(f=[]);c=!1;if(d=N.exec(h))f.push(c=new q(d.shift())),h=h.slice(c.length),c.type=d[0].replace(L," ");for(g in e.filter)(d=W[g].exec(h))&&(!j[g]||(d=j[g](d,r,!0)))&&(f.push(c=new q(d.shift())),h=h.slice(c.length),c.type=g,c.matches=d);if(!c)break}return b?h.length:h?bc.error(a):C(a,i).slice(0)}function bi(a,b,d){var e=b.dir,f=d&&b.dir==="parentNode",g=u++;return b.first?function(b,c,d){while(b=b[e])if(f||b.nodeType===1)return a(b,c,d)}:function(b,d,h){if(!h){var i,j=t+" "+g+" ",k=j+c;while(b=b[e])if(f||b.nodeType===1){if((i=b[o])===k)return b.sizset;if(typeof i=="string"&&i.indexOf(j)===0){if(b.sizset)return b}else{b[o]=k;if(a(b,d,h))return b.sizset=!0,b;b.sizset=!1}}}else while(b=b[e])if(f||b.nodeType===1)if(a(b,d,h))return b}}function bj(a){return a.length>1?function(b,c,d){var e=a.length;while(e--)if(!a[e](b,c,d))return!1;return!0}:a[0]}function bk(a,b,c,d,e){var f,g=[],h=0,i=a.length,j=b!=null;for(;h<i;h++)if(f=a[h])if(!c||c(f,d,e))g.push(f),j&&b.push(h);return g}function bl(a,b,c,d,e,f){return d&&!d[o]&&(d=bl(d)),e&&!e[o]&&(e=bl(e,f)),z(function(f,g,h,i){if(f&&e)return;var j,k,l,m=[],n=[],o=g.length,p=f||bo(b||"*",h.nodeType?[h]:h,[],f),q=a&&(f||!b)?bk(p,m,a,h,i):p,r=c?e||(f?a:o||d)?[]:g:q;c&&c(q,r,h,i);if(d){l=bk(r,n),d(l,[],h,i),j=l.length;while(j--)if(k=l[j])r[n[j]]=!(q[n[j]]=k)}if(f){j=a&&r.length;while(j--)if(k=r[j])f[m[j]]=!(g[m[j]]=k)}else r=bk(r===g?r.splice(o,r.length):r),e?e(null,g,r,i):w.apply(g,r)})}function bm(a){var b,c,d,f=a.length,g=e.relative[a[0].type],h=g||e.relative[" "],i=g?1:0,j=bi(function(a){return a===b},h,!0),k=bi(function(a){return y.call(b,a)>-1},h,!0),m=[function(a,c,d){return!g&&(d||c!==l)||((b=c).nodeType?j(a,c,d):k(a,c,d))}];for(;i<f;i++)if(c=e.relative[a[i].type])m=[bi(bj(m),c)];else{c=e.filter[a[i].type].apply(null,a[i].matches);if(c[o]){d=++i;for(;d<f;d++)if(e.relative[a[d].type])break;return bl(i>1&&bj(m),i>1&&a.slice(0,i-1).join("").replace(L,"$1"),c,i<d&&bm(a.slice(i,d)),d<f&&bm(a=a.slice(d)),d<f&&a.join(""))}m.push(c)}return bj(m)}function bn(a,b){var d=b.length>0,f=a.length>0,g=function(h,i,j,k,m){var n,o,p,q=[],s=0,u="0",x=h&&[],y=m!=null,z=l,A=h||f&&e.find.TAG("*",m&&i.parentNode||i),B=t+=z==null?1:Math.E;y&&(l=i!==r&&i,c=g.el);for(;(n=A[u])!=null;u++){if(f&&n){for(o=0;p=a[o];o++)if(p(n,i,j)){k.push(n);break}y&&(t=B,c=++g.el)}d&&((n=!p&&n)&&s--,h&&x.push(n))}s+=u;if(d&&u!==s){for(o=0;p=b[o];o++)p(x,q,i,j);if(h){if(s>0)while(u--)!x[u]&&!q[u]&&(q[u]=v.call(k));q=bk(q)}w.apply(k,q),y&&!h&&q.length>0&&s+b.length>1&&bc.uniqueSort(k)}return y&&(t=B,l=z),x};return g.el=0,d?z(g):g}function bo(a,b,c,d){var e=0,f=b.length;for(;e<f;e++)bc(a,b[e],c,d);return c}function bp(a,b,c,d,f){var g,h,j,k,l,m=bh(a),n=m.length;if(!d&&m.length===1){h=m[0]=m[0].slice(0);if(h.length>2&&(j=h[0]).type==="ID"&&b.nodeType===9&&!f&&e.relative[h[1].type]){b=e.find.ID(j.matches[0].replace(V,""),b,f)[0];if(!b)return c;a=a.slice(h.shift().length)}for(g=W.POS.test(a)?-1:h.length-1;g>=0;g--){j=h[g];if(e.relative[k=j.type])break;if(l=e.find[k])if(d=l(j.matches[0].replace(V,""),R.test(h[0].type)&&b.parentNode||b,f)){h.splice(g,1),a=d.length&&h.join("");if(!a)return w.apply(c,x.call(d,0)),c;break}}}return i(a,m)(d,b,f,c,R.test(a)),c}function bq(){}var c,d,e,f,g,h,i,j,k,l,m=!0,n="undefined",o=("sizcache"+Math.random()).replace(".",""),q=String,r=a.document,s=r.documentElement,t=0,u=0,v=[].pop,w=[].push,x=[].slice,y=[].indexOf||function(a){var b=0,c=this.length;for(;b<c;b++)if(this[b]===a)return b;return-1},z=function(a,b){return a[o]=b==null||b,a},A=function(){var a={},b=[];return z(function(c,d){return b.push(c)>e.cacheLength&&delete a[b.shift()],a[c]=d},a)},B=A(),C=A(),D=A(),E="[\\x20\\t\\r\\n\\f]",F="(?:\\\\.|[-\\w]|[^\\x00-\\xa0])+",G=F.replace("w","w#"),H="([*^$|!~]?=)",I="\\["+E+"*("+F+")"+E+"*(?:"+H+E+"*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|("+G+")|)|)"+E+"*\\]",J=":("+F+")(?:\\((?:(['\"])((?:\\\\.|[^\\\\])*?)\\2|([^()[\\]]*|(?:(?:"+I+")|[^:]|\\\\.)*|.*))\\)|)",K=":(even|odd|eq|gt|lt|nth|first|last)(?:\\("+E+"*((?:-\\d)?\\d*)"+E+"*\\)|)(?=[^-]|$)",L=new RegExp("^"+E+"+|((?:^|[^\\\\])(?:\\\\.)*)"+E+"+$","g"),M=new RegExp("^"+E+"*,"+E+"*"),N=new RegExp("^"+E+"*([\\x20\\t\\r\\n\\f>+~])"+E+"*"),O=new RegExp(J),P=/^(?:#([\w\-]+)|(\w+)|\.([\w\-]+))$/,Q=/^:not/,R=/[\x20\t\r\n\f]*[+~]/,S=/:not\($/,T=/h\d/i,U=/input|select|textarea|button/i,V=/\\(?!\\)/g,W={ID:new RegExp("^#("+F+")"),CLASS:new RegExp("^\\.("+F+")"),NAME:new RegExp("^\\[name=['\"]?("+F+")['\"]?\\]"),TAG:new RegExp("^("+F.replace("w","w*")+")"),ATTR:new RegExp("^"+I),PSEUDO:new RegExp("^"+J),POS:new RegExp(K,"i"),CHILD:new RegExp("^:(only|nth|first|last)-child(?:\\("+E+"*(even|odd|(([+-]|)(\\d*)n|)"+E+"*(?:([+-]|)"+E+"*(\\d+)|))"+E+"*\\)|)","i"),needsContext:new RegExp("^"+E+"*[>+~]|"+K,"i")},X=function(a){var b=r.createElement("div");try{return a(b)}catch(c){return!1}finally{b=null}},Y=X(function(a){return a.appendChild(r.createComment("")),!a.getElementsByTagName("*").length}),Z=X(function(a){return a.innerHTML="<a href='#'></a>",a.firstChild&&typeof a.firstChild.getAttribute!==n&&a.firstChild.getAttribute("href")==="#"}),$=X(function(a){a.innerHTML="<select></select>";var b=typeof a.lastChild.getAttribute("multiple");return b!=="boolean"&&b!=="string"}),_=X(function(a){return a.innerHTML="<div class='hidden e'></div><div class='hidden'></div>",!a.getElementsByClassName||!a.getElementsByClassName("e").length?!1:(a.lastChild.className="e",a.getElementsByClassName("e").length===2)}),ba=X(function(a){a.id=o+0,a.innerHTML="<a name='"+o+"'></a><div name='"+o+"'></div>",s.insertBefore(a,s.firstChild);var b=r.getElementsByName&&r.getElementsByName(o).length===2+r.getElementsByName(o+0).length;return d=!r.getElementById(o),s.removeChild(a),b});try{x.call(s.childNodes,0)[0].nodeType}catch(bb){x=function(a){var b,c=[];for(;b=this[a];a++)c.push(b);return c}}bc.matches=function(a,b){return bc(a,null,null,b)},bc.matchesSelector=function(a,b){return bc(b,null,null,[a]).length>0},f=bc.getText=function(a){var b,c="",d=0,e=a.nodeType;if(e){if(e===1||e===9||e===11){if(typeof a.textContent=="string")return a.textContent;for(a=a.firstChild;a;a=a.nextSibling)c+=f(a)}else if(e===3||e===4)return a.nodeValue}else for(;b=a[d];d++)c+=f(b);return c},g=bc.isXML=function(a){var b=a&&(a.ownerDocument||a).documentElement;return b?b.nodeName!=="HTML":!1},h=bc.contains=s.contains?function(a,b){var c=a.nodeType===9?a.documentElement:a,d=b&&b.parentNode;return a===d||!!(d&&d.nodeType===1&&c.contains&&c.contains(d))}:s.compareDocumentPosition?function(a,b){return b&&!!(a.compareDocumentPosition(b)&16)}:function(a,b){while(b=b.parentNode)if(b===a)return!0;return!1},bc.attr=function(a,b){var c,d=g(a);return d||(b=b.toLowerCase()),(c=e.attrHandle[b])?c(a):d||$?a.getAttribute(b):(c=a.getAttributeNode(b),c?typeof a[b]=="boolean"?a[b]?b:null:c.specified?c.value:null:null)},e=bc.selectors={cacheLength:50,createPseudo:z,match:W,attrHandle:Z?{}:{href:function(a){return a.getAttribute("href",2)},type:function(a){return a.getAttribute("type")}},find:{ID:d?function(a,b,c){if(typeof b.getElementById!==n&&!c){var d=b.getElementById(a);return d&&d.parentNode?[d]:[]}}:function(a,c,d){if(typeof c.getElementById!==n&&!d){var e=c.getElementById(a);return e?e.id===a||typeof e.getAttributeNode!==n&&e.getAttributeNode("id").value===a?[e]:b:[]}},TAG:Y?function(a,b){if(typeof b.getElementsByTagName!==n)return b.getElementsByTagName(a)}:function(a,b){var c=b.getElementsByTagName(a);if(a==="*"){var d,e=[],f=0;for(;d=c[f];f++)d.nodeType===1&&e.push(d);return e}return c},NAME:ba&&function(a,b){if(typeof b.getElementsByName!==n)return b.getElementsByName(name)},CLASS:_&&function(a,b,c){if(typeof b.getElementsByClassName!==n&&!c)return b.getElementsByClassName(a)}},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(a){return a[1]=a[1].replace(V,""),a[3]=(a[4]||a[5]||"").replace(V,""),a[2]==="~="&&(a[3]=" "+a[3]+" "),a.slice(0,4)},CHILD:function(a){return a[1]=a[1].toLowerCase(),a[1]==="nth"?(a[2]||bc.error(a[0]),a[3]=+(a[3]?a[4]+(a[5]||1):2*(a[2]==="even"||a[2]==="odd")),a[4]=+(a[6]+a[7]||a[2]==="odd")):a[2]&&bc.error(a[0]),a},PSEUDO:function(a){var b,c;if(W.CHILD.test(a[0]))return null;if(a[3])a[2]=a[3];else if(b=a[4])O.test(b)&&(c=bh(b,!0))&&(c=b.indexOf(")",b.length-c)-b.length)&&(b=b.slice(0,c),a[0]=a[0].slice(0,c)),a[2]=b;return a.slice(0,3)}},filter:{ID:d?function(a){return a=a.replace(V,""),function(b){return b.getAttribute("id")===a}}:function(a){return a=a.replace(V,""),function(b){var c=typeof b.getAttributeNode!==n&&b.getAttributeNode("id");return c&&c.value===a}},TAG:function(a){return a==="*"?function(){return!0}:(a=a.replace(V,"").toLowerCase(),function(b){return b.nodeName&&b.nodeName.toLowerCase()===a})},CLASS:function(a){var b=B[o][a];return b||(b=B(a,new RegExp("(^|"+E+")"+a+"("+E+"|$)"))),function(a){return b.test(a.className||typeof a.getAttribute!==n&&a.getAttribute("class")||"")}},ATTR:function(a,b,c){return function(d,e){var f=bc.attr(d,a);return f==null?b==="!=":b?(f+="",b==="="?f===c:b==="!="?f!==c:b==="^="?c&&f.indexOf(c)===0:b==="*="?c&&f.indexOf(c)>-1:b==="$="?c&&f.substr(f.length-c.length)===c:b==="~="?(" "+f+" ").indexOf(c)>-1:b==="|="?f===c||f.substr(0,c.length+1)===c+"-":!1):!0}},CHILD:function(a,b,c,d){return a==="nth"?function(a){var b,e,f=a.parentNode;if(c===1&&d===0)return!0;if(f){e=0;for(b=f.firstChild;b;b=b.nextSibling)if(b.nodeType===1){e++;if(a===b)break}}return e-=d,e===c||e%c===0&&e/c>=0}:function(b){var c=b;switch(a){case"only":case"first":while(c=c.previousSibling)if(c.nodeType===1)return!1;if(a==="first")return!0;c=b;case"last":while(c=c.nextSibling)if(c.nodeType===1)return!1;return!0}}},PSEUDO:function(a,b){var c,d=e.pseudos[a]||e.setFilters[a.toLowerCase()]||bc.error("unsupported pseudo: "+a);return d[o]?d(b):d.length>1?(c=[a,a,"",b],e.setFilters.hasOwnProperty(a.toLowerCase())?z(function(a,c){var e,f=d(a,b),g=f.length;while(g--)e=y.call(a,f[g]),a[e]=!(c[e]=f[g])}):function(a){return d(a,0,c)}):d}},pseudos:{not:z(function(a){var b=[],c=[],d=i(a.replace(L,"$1"));return d[o]?z(function(a,b,c,e){var f,g=d(a,null,e,[]),h=a.length;while(h--)if(f=g[h])a[h]=!(b[h]=f)}):function(a,e,f){return b[0]=a,d(b,null,f,c),!c.pop()}}),has:z(function(a){return function(b){return bc(a,b).length>0}}),contains:z(function(a){return function(b){return(b.textContent||b.innerText||f(b)).indexOf(a)>-1}}),enabled:function(a){return a.disabled===!1},disabled:function(a){return a.disabled===!0},checked:function(a){var b=a.nodeName.toLowerCase();return b==="input"&&!!a.checked||b==="option"&&!!a.selected},selected:function(a){return a.parentNode&&a.parentNode.selectedIndex,a.selected===!0},parent:function(a){return!e.pseudos.empty(a)},empty:function(a){var b;a=a.firstChild;while(a){if(a.nodeName>"@"||(b=a.nodeType)===3||b===4)return!1;a=a.nextSibling}return!0},header:function(a){return T.test(a.nodeName)},text:function(a){var b,c;return a.nodeName.toLowerCase()==="input"&&(b=a.type)==="text"&&((c=a.getAttribute("type"))==null||c.toLowerCase()===b)},radio:bd("radio"),checkbox:bd("checkbox"),file:bd("file"),password:bd("password"),image:bd("image"),submit:be("submit"),reset:be("reset"),button:function(a){var b=a.nodeName.toLowerCase();return b==="input"&&a.type==="button"||b==="button"},input:function(a){return U.test(a.nodeName)},focus:function(a){var b=a.ownerDocument;return a===b.activeElement&&(!b.hasFocus||b.hasFocus())&&(!!a.type||!!a.href)},active:function(a){return a===a.ownerDocument.activeElement},first:bf(function(a,b,c){return[0]}),last:bf(function(a,b,c){return[b-1]}),eq:bf(function(a,b,c){return[c<0?c+b:c]}),even:bf(function(a,b,c){for(var d=0;d<b;d+=2)a.push(d);return a}),odd:bf(function(a,b,c){for(var d=1;d<b;d+=2)a.push(d);return a}),lt:bf(function(a,b,c){for(var d=c<0?c+b:c;--d>=0;)a.push(d);return a}),gt:bf(function(a,b,c){for(var d=c<0?c+b:c;++d<b;)a.push(d);return a})}},j=s.compareDocumentPosition?function(a,b){return a===b?(k=!0,0):(!a.compareDocumentPosition||!b.compareDocumentPosition?a.compareDocumentPosition:a.compareDocumentPosition(b)&4)?-1:1}:function(a,b){if(a===b)return k=!0,0;if(a.sourceIndex&&b.sourceIndex)return a.sourceIndex-b.sourceIndex;var c,d,e=[],f=[],g=a.parentNode,h=b.parentNode,i=g;if(g===h)return bg(a,b);if(!g)return-1;if(!h)return 1;while(i)e.unshift(i),i=i.parentNode;i=h;while(i)f.unshift(i),i=i.parentNode;c=e.length,d=f.length;for(var j=0;j<c&&j<d;j++)if(e[j]!==f[j])return bg(e[j],f[j]);return j===c?bg(a,f[j],-1):bg(e[j],b,1)},[0,0].sort(j),m=!k,bc.uniqueSort=function(a){var b,c=1;k=m,a.sort(j);if(k)for(;b=a[c];c++)b===a[c-1]&&a.splice(c--,1);return a},bc.error=function(a){throw new Error("Syntax error, unrecognized expression: "+a)},i=bc.compile=function(a,b){var c,d=[],e=[],f=D[o][a];if(!f){b||(b=bh(a)),c=b.length;while(c--)f=bm(b[c]),f[o]?d.push(f):e.push(f);f=D(a,bn(e,d))}return f},r.querySelectorAll&&function(){var a,b=bp,c=/'|\\/g,d=/\=[\x20\t\r\n\f]*([^'"\]]*)[\x20\t\r\n\f]*\]/g,e=[":focus"],f=[":active",":focus"],h=s.matchesSelector||s.mozMatchesSelector||s.webkitMatchesSelector||s.oMatchesSelector||s.msMatchesSelector;X(function(a){a.innerHTML="<select><option selected=''></option></select>",a.querySelectorAll("[selected]").length||e.push("\\["+E+"*(?:checked|disabled|ismap|multiple|readonly|selected|value)"),a.querySelectorAll(":checked").length||e.push(":checked")}),X(function(a){a.innerHTML="<p test=''></p>",a.querySelectorAll("[test^='']").length&&e.push("[*^$]="+E+"*(?:\"\"|'')"),a.innerHTML="<input type='hidden'/>",a.querySelectorAll(":enabled").length||e.push(":enabled",":disabled")}),e=new RegExp(e.join("|")),bp=function(a,d,f,g,h){if(!g&&!h&&(!e||!e.test(a))){var i,j,k=!0,l=o,m=d,n=d.nodeType===9&&a;if(d.nodeType===1&&d.nodeName.toLowerCase()!=="object"){i=bh(a),(k=d.getAttribute("id"))?l=k.replace(c,"\\$&"):d.setAttribute("id",l),l="[id='"+l+"'] ",j=i.length;while(j--)i[j]=l+i[j].join("");m=R.test(a)&&d.parentNode||d,n=i.join(",")}if(n)try{return w.apply(f,x.call(m.querySelectorAll(n),0)),f}catch(p){}finally{k||d.removeAttribute("id")}}return b(a,d,f,g,h)},h&&(X(function(b){a=h.call(b,"div");try{h.call(b,"[test!='']:sizzle"),f.push("!=",J)}catch(c){}}),f=new RegExp(f.join("|")),bc.matchesSelector=function(b,c){c=c.replace(d,"='$1']");if(!g(b)&&!f.test(c)&&(!e||!e.test(c)))try{var i=h.call(b,c);if(i||a||b.document&&b.document.nodeType!==11)return i}catch(j){}return bc(c,null,null,[b]).length>0})}(),e.pseudos.nth=e.pseudos.eq,e.filters=bq.prototype=e.pseudos,e.setFilters=new bq,bc.attr=p.attr,p.find=bc,p.expr=bc.selectors,p.expr[":"]=p.expr.pseudos,p.unique=bc.uniqueSort,p.text=bc.getText,p.isXMLDoc=bc.isXML,p.contains=bc.contains}(a);var bc=/Until$/,bd=/^(?:parents|prev(?:Until|All))/,be=/^.[^:#\[\.,]*$/,bf=p.expr.match.needsContext,bg={children:!0,contents:!0,next:!0,prev:!0};p.fn.extend({find:function(a){var b,c,d,e,f,g,h=this;if(typeof a!="string")return p(a).filter(function(){for(b=0,c=h.length;b<c;b++)if(p.contains(h[b],this))return!0});g=this.pushStack("","find",a);for(b=0,c=this.length;b<c;b++){d=g.length,p.find(a,this[b],g);if(b>0)for(e=d;e<g.length;e++)for(f=0;f<d;f++)if(g[f]===g[e]){g.splice(e--,1);break}}return g},has:function(a){var b,c=p(a,this),d=c.length;return this.filter(function(){for(b=0;b<d;b++)if(p.contains(this,c[b]))return!0})},not:function(a){return this.pushStack(bj(this,a,!1),"not",a)},filter:function(a){return this.pushStack(bj(this,a,!0),"filter",a)},is:function(a){return!!a&&(typeof a=="string"?bf.test(a)?p(a,this.context).index(this[0])>=0:p.filter(a,this).length>0:this.filter(a).length>0)},closest:function(a,b){var c,d=0,e=this.length,f=[],g=bf.test(a)||typeof a!="string"?p(a,b||this.context):0;for(;d<e;d++){c=this[d];while(c&&c.ownerDocument&&c!==b&&c.nodeType!==11){if(g?g.index(c)>-1:p.find.matchesSelector(c,a)){f.push(c);break}c=c.parentNode}}return f=f.length>1?p.unique(f):f,this.pushStack(f,"closest",a)},index:function(a){return a?typeof a=="string"?p.inArray(this[0],p(a)):p.inArray(a.jquery?a[0]:a,this):this[0]&&this[0].parentNode?this.prevAll().length:-1},add:function(a,b){var c=typeof a=="string"?p(a,b):p.makeArray(a&&a.nodeType?[a]:a),d=p.merge(this.get(),c);return this.pushStack(bh(c[0])||bh(d[0])?d:p.unique(d))},addBack:function(a){return this.add(a==null?this.prevObject:this.prevObject.filter(a))}}),p.fn.andSelf=p.fn.addBack,p.each({parent:function(a){var b=a.parentNode;return b&&b.nodeType!==11?b:null},parents:function(a){return p.dir(a,"parentNode")},parentsUntil:function(a,b,c){return p.dir(a,"parentNode",c)},next:function(a){return bi(a,"nextSibling")},prev:function(a){return bi(a,"previousSibling")},nextAll:function(a){return p.dir(a,"nextSibling")},prevAll:function(a){return p.dir(a,"previousSibling")},nextUntil:function(a,b,c){return p.dir(a,"nextSibling",c)},prevUntil:function(a,b,c){return p.dir(a,"previousSibling",c)},siblings:function(a){return p.sibling((a.parentNode||{}).firstChild,a)},children:function(a){return p.sibling(a.firstChild)},contents:function(a){return p.nodeName(a,"iframe")?a.contentDocument||a.contentWindow.document:p.merge([],a.childNodes)}},function(a,b){p.fn[a]=function(c,d){var e=p.map(this,b,c);return bc.test(a)||(d=c),d&&typeof d=="string"&&(e=p.filter(d,e)),e=this.length>1&&!bg[a]?p.unique(e):e,this.length>1&&bd.test(a)&&(e=e.reverse()),this.pushStack(e,a,k.call(arguments).join(","))}}),p.extend({filter:function(a,b,c){return c&&(a=":not("+a+")"),b.length===1?p.find.matchesSelector(b[0],a)?[b[0]]:[]:p.find.matches(a,b)},dir:function(a,c,d){var e=[],f=a[c];while(f&&f.nodeType!==9&&(d===b||f.nodeType!==1||!p(f).is(d)))f.nodeType===1&&e.push(f),f=f[c];return e},sibling:function(a,b){var c=[];for(;a;a=a.nextSibling)a.nodeType===1&&a!==b&&c.push(a);return c}});var bl="abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",bm=/ jQuery\d+="(?:null|\d+)"/g,bn=/^\s+/,bo=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,bp=/<([\w:]+)/,bq=/<tbody/i,br=/<|&#?\w+;/,bs=/<(?:script|style|link)/i,bt=/<(?:script|object|embed|option|style)/i,bu=new RegExp("<(?:"+bl+")[\\s/>]","i"),bv=/^(?:checkbox|radio)$/,bw=/checked\s*(?:[^=]|=\s*.checked.)/i,bx=/\/(java|ecma)script/i,by=/^\s*<!(?:\[CDATA\[|\-\-)|[\]\-]{2}>\s*$/g,bz={option:[1,"<select multiple='multiple'>","</select>"],legend:[1,"<fieldset>","</fieldset>"],thead:[1,"<table>","</table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],col:[2,"<table><tbody></tbody><colgroup>","</colgroup></table>"],area:[1,"<map>","</map>"],_default:[0,"",""]},bA=bk(e),bB=bA.appendChild(e.createElement("div"));bz.optgroup=bz.option,bz.tbody=bz.tfoot=bz.colgroup=bz.caption=bz.thead,bz.th=bz.td,p.support.htmlSerialize||(bz._default=[1,"X<div>","</div>"]),p.fn.extend({text:function(a){return p.access(this,function(a){return a===b?p.text(this):this.empty().append((this[0]&&this[0].ownerDocument||e).createTextNode(a))},null,a,arguments.length)},wrapAll:function(a){if(p.isFunction(a))return this.each(function(b){p(this).wrapAll(a.call(this,b))});if(this[0]){var b=p(a,this[0].ownerDocument).eq(0).clone(!0);this[0].parentNode&&b.insertBefore(this[0]),b.map(function(){var a=this;while(a.firstChild&&a.firstChild.nodeType===1)a=a.firstChild;return a}).append(this)}return this},wrapInner:function(a){return p.isFunction(a)?this.each(function(b){p(this).wrapInner(a.call(this,b))}):this.each(function(){var b=p(this),c=b.contents();c.length?c.wrapAll(a):b.append(a)})},wrap:function(a){var b=p.isFunction(a);return this.each(function(c){p(this).wrapAll(b?a.call(this,c):a)})},unwrap:function(){return this.parent().each(function(){p.nodeName(this,"body")||p(this).replaceWith(this.childNodes)}).end()},append:function(){return this.domManip(arguments,!0,function(a){(this.nodeType===1||this.nodeType===11)&&this.appendChild(a)})},prepend:function(){return this.domManip(arguments,!0,function(a){(this.nodeType===1||this.nodeType===11)&&this.insertBefore(a,this.firstChild)})},before:function(){if(!bh(this[0]))return this.domManip(arguments,!1,function(a){this.parentNode.insertBefore(a,this)});if(arguments.length){var a=p.clean(arguments);return this.pushStack(p.merge(a,this),"before",this.selector)}},after:function(){if(!bh(this[0]))return this.domManip(arguments,!1,function(a){this.parentNode.insertBefore(a,this.nextSibling)});if(arguments.length){var a=p.clean(arguments);return this.pushStack(p.merge(this,a),"after",this.selector)}},remove:function(a,b){var c,d=0;for(;(c=this[d])!=null;d++)if(!a||p.filter(a,[c]).length)!b&&c.nodeType===1&&(p.cleanData(c.getElementsByTagName("*")),p.cleanData([c])),c.parentNode&&c.parentNode.removeChild(c);return this},empty:function(){var a,b=0;for(;(a=this[b])!=null;b++){a.nodeType===1&&p.cleanData(a.getElementsByTagName("*"));while(a.firstChild)a.removeChild(a.firstChild)}return this},clone:function(a,b){return a=a==null?!1:a,b=b==null?a:b,this.map(function(){return p.clone(this,a,b)})},html:function(a){return p.access(this,function(a){var c=this[0]||{},d=0,e=this.length;if(a===b)return c.nodeType===1?c.innerHTML.replace(bm,""):b;if(typeof a=="string"&&!bs.test(a)&&(p.support.htmlSerialize||!bu.test(a))&&(p.support.leadingWhitespace||!bn.test(a))&&!bz[(bp.exec(a)||["",""])[1].toLowerCase()]){a=a.replace(bo,"<$1></$2>");try{for(;d<e;d++)c=this[d]||{},c.nodeType===1&&(p.cleanData(c.getElementsByTagName("*")),c.innerHTML=a);c=0}catch(f){}}c&&this.empty().append(a)},null,a,arguments.length)},replaceWith:function(a){return bh(this[0])?this.length?this.pushStack(p(p.isFunction(a)?a():a),"replaceWith",a):this:p.isFunction(a)?this.each(function(b){var c=p(this),d=c.html();c.replaceWith(a.call(this,b,d))}):(typeof a!="string"&&(a=p(a).detach()),this.each(function(){var b=this.nextSibling,c=this.parentNode;p(this).remove(),b?p(b).before(a):p(c).append(a)}))},detach:function(a){return this.remove(a,!0)},domManip:function(a,c,d){a=[].concat.apply([],a);var e,f,g,h,i=0,j=a[0],k=[],l=this.length;if(!p.support.checkClone&&l>1&&typeof j=="string"&&bw.test(j))return this.each(function(){p(this).domManip(a,c,d)});if(p.isFunction(j))return this.each(function(e){var f=p(this);a[0]=j.call(this,e,c?f.html():b),f.domManip(a,c,d)});if(this[0]){e=p.buildFragment(a,this,k),g=e.fragment,f=g.firstChild,g.childNodes.length===1&&(g=f);if(f){c=c&&p.nodeName(f,"tr");for(h=e.cacheable||l-1;i<l;i++)d.call(c&&p.nodeName(this[i],"table")?bC(this[i],"tbody"):this[i],i===h?g:p.clone(g,!0,!0))}g=f=null,k.length&&p.each(k,function(a,b){b.src?p.ajax?p.ajax({url:b.src,type:"GET",dataType:"script",async:!1,global:!1,"throws":!0}):p.error("no ajax"):p.globalEval((b.text||b.textContent||b.innerHTML||"").replace(by,"")),b.parentNode&&b.parentNode.removeChild(b)})}return this}}),p.buildFragment=function(a,c,d){var f,g,h,i=a[0];return c=c||e,c=!c.nodeType&&c[0]||c,c=c.ownerDocument||c,a.length===1&&typeof i=="string"&&i.length<512&&c===e&&i.charAt(0)==="<"&&!bt.test(i)&&(p.support.checkClone||!bw.test(i))&&(p.support.html5Clone||!bu.test(i))&&(g=!0,f=p.fragments[i],h=f!==b),f||(f=c.createDocumentFragment(),p.clean(a,c,f,d),g&&(p.fragments[i]=h&&f)),{fragment:f,cacheable:g}},p.fragments={},p.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(a,b){p.fn[a]=function(c){var d,e=0,f=[],g=p(c),h=g.length,i=this.length===1&&this[0].parentNode;if((i==null||i&&i.nodeType===11&&i.childNodes.length===1)&&h===1)return g[b](this[0]),this;for(;e<h;e++)d=(e>0?this.clone(!0):this).get(),p(g[e])[b](d),f=f.concat(d);return this.pushStack(f,a,g.selector)}}),p.extend({clone:function(a,b,c){var d,e,f,g;p.support.html5Clone||p.isXMLDoc(a)||!bu.test("<"+a.nodeName+">")?g=a.cloneNode(!0):(bB.innerHTML=a.outerHTML,bB.removeChild(g=bB.firstChild));if((!p.support.noCloneEvent||!p.support.noCloneChecked)&&(a.nodeType===1||a.nodeType===11)&&!p.isXMLDoc(a)){bE(a,g),d=bF(a),e=bF(g);for(f=0;d[f];++f)e[f]&&bE(d[f],e[f])}if(b){bD(a,g);if(c){d=bF(a),e=bF(g);for(f=0;d[f];++f)bD(d[f],e[f])}}return d=e=null,g},clean:function(a,b,c,d){var f,g,h,i,j,k,l,m,n,o,q,r,s=b===e&&bA,t=[];if(!b||typeof b.createDocumentFragment=="undefined")b=e;for(f=0;(h=a[f])!=null;f++){typeof h=="number"&&(h+="");if(!h)continue;if(typeof h=="string")if(!br.test(h))h=b.createTextNode(h);else{s=s||bk(b),l=b.createElement("div"),s.appendChild(l),h=h.replace(bo,"<$1></$2>"),i=(bp.exec(h)||["",""])[1].toLowerCase(),j=bz[i]||bz._default,k=j[0],l.innerHTML=j[1]+h+j[2];while(k--)l=l.lastChild;if(!p.support.tbody){m=bq.test(h),n=i==="table"&&!m?l.firstChild&&l.firstChild.childNodes:j[1]==="<table>"&&!m?l.childNodes:[];for(g=n.length-1;g>=0;--g)p.nodeName(n[g],"tbody")&&!n[g].childNodes.length&&n[g].parentNode.removeChild(n[g])}!p.support.leadingWhitespace&&bn.test(h)&&l.insertBefore(b.createTextNode(bn.exec(h)[0]),l.firstChild),h=l.childNodes,l.parentNode.removeChild(l)}h.nodeType?t.push(h):p.merge(t,h)}l&&(h=l=s=null);if(!p.support.appendChecked)for(f=0;(h=t[f])!=null;f++)p.nodeName(h,"input")?bG(h):typeof h.getElementsByTagName!="undefined"&&p.grep(h.getElementsByTagName("input"),bG);if(c){q=function(a){if(!a.type||bx.test(a.type))return d?d.push(a.parentNode?a.parentNode.removeChild(a):a):c.appendChild(a)};for(f=0;(h=t[f])!=null;f++)if(!p.nodeName(h,"script")||!q(h))c.appendChild(h),typeof h.getElementsByTagName!="undefined"&&(r=p.grep(p.merge([],h.getElementsByTagName("script")),q),t.splice.apply(t,[f+1,0].concat(r)),f+=r.length)}return t},cleanData:function(a,b){var c,d,e,f,g=0,h=p.expando,i=p.cache,j=p.support.deleteExpando,k=p.event.special;for(;(e=a[g])!=null;g++)if(b||p.acceptData(e)){d=e[h],c=d&&i[d];if(c){if(c.events)for(f in c.events)k[f]?p.event.remove(e,f):p.removeEvent(e,f,c.handle);i[d]&&(delete i[d],j?delete e[h]:e.removeAttribute?e.removeAttribute(h):e[h]=null,p.deletedIds.push(d))}}}}),function(){var a,b;p.uaMatch=function(a){a=a.toLowerCase();var b=/(chrome)[ \/]([\w.]+)/.exec(a)||/(webkit)[ \/]([\w.]+)/.exec(a)||/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(a)||/(msie) ([\w.]+)/.exec(a)||a.indexOf("compatible")<0&&/(mozilla)(?:.*? rv:([\w.]+)|)/.exec(a)||[];return{browser:b[1]||"",version:b[2]||"0"}},a=p.uaMatch(g.userAgent),b={},a.browser&&(b[a.browser]=!0,b.version=a.version),b.chrome?b.webkit=!0:b.webkit&&(b.safari=!0),p.browser=b,p.sub=function(){function a(b,c){return new a.fn.init(b,c)}p.extend(!0,a,this),a.superclass=this,a.fn=a.prototype=this(),a.fn.constructor=a,a.sub=this.sub,a.fn.init=function c(c,d){return d&&d instanceof p&&!(d instanceof a)&&(d=a(d)),p.fn.init.call(this,c,d,b)},a.fn.init.prototype=a.fn;var b=a(e);return a}}();var bH,bI,bJ,bK=/alpha\([^)]*\)/i,bL=/opacity=([^)]*)/,bM=/^(top|right|bottom|left)$/,bN=/^(none|table(?!-c[ea]).+)/,bO=/^margin/,bP=new RegExp("^("+q+")(.*)$","i"),bQ=new RegExp("^("+q+")(?!px)[a-z%]+$","i"),bR=new RegExp("^([-+])=("+q+")","i"),bS={},bT={position:"absolute",visibility:"hidden",display:"block"},bU={letterSpacing:0,fontWeight:400},bV=["Top","Right","Bottom","Left"],bW=["Webkit","O","Moz","ms"],bX=p.fn.toggle;p.fn.extend({css:function(a,c){return p.access(this,function(a,c,d){return d!==b?p.style(a,c,d):p.css(a,c)},a,c,arguments.length>1)},show:function(){return b$(this,!0)},hide:function(){return b$(this)},toggle:function(a,b){var c=typeof a=="boolean";return p.isFunction(a)&&p.isFunction(b)?bX.apply(this,arguments):this.each(function(){(c?a:bZ(this))?p(this).show():p(this).hide()})}}),p.extend({cssHooks:{opacity:{get:function(a,b){if(b){var c=bH(a,"opacity");return c===""?"1":c}}}},cssNumber:{fillOpacity:!0,fontWeight:!0,lineHeight:!0,opacity:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":p.support.cssFloat?"cssFloat":"styleFloat"},style:function(a,c,d,e){if(!a||a.nodeType===3||a.nodeType===8||!a.style)return;var f,g,h,i=p.camelCase(c),j=a.style;c=p.cssProps[i]||(p.cssProps[i]=bY(j,i)),h=p.cssHooks[c]||p.cssHooks[i];if(d===b)return h&&"get"in h&&(f=h.get(a,!1,e))!==b?f:j[c];g=typeof d,g==="string"&&(f=bR.exec(d))&&(d=(f[1]+1)*f[2]+parseFloat(p.css(a,c)),g="number");if(d==null||g==="number"&&isNaN(d))return;g==="number"&&!p.cssNumber[i]&&(d+="px");if(!h||!("set"in h)||(d=h.set(a,d,e))!==b)try{j[c]=d}catch(k){}},css:function(a,c,d,e){var f,g,h,i=p.camelCase(c);return c=p.cssProps[i]||(p.cssProps[i]=bY(a.style,i)),h=p.cssHooks[c]||p.cssHooks[i],h&&"get"in h&&(f=h.get(a,!0,e)),f===b&&(f=bH(a,c)),f==="normal"&&c in bU&&(f=bU[c]),d||e!==b?(g=parseFloat(f),d||p.isNumeric(g)?g||0:f):f},swap:function(a,b,c){var d,e,f={};for(e in b)f[e]=a.style[e],a.style[e]=b[e];d=c.call(a);for(e in b)a.style[e]=f[e];return d}}),a.getComputedStyle?bH=function(b,c){var d,e,f,g,h=a.getComputedStyle(b,null),i=b.style;return h&&(d=h[c],d===""&&!p.contains(b.ownerDocument,b)&&(d=p.style(b,c)),bQ.test(d)&&bO.test(c)&&(e=i.width,f=i.minWidth,g=i.maxWidth,i.minWidth=i.maxWidth=i.width=d,d=h.width,i.width=e,i.minWidth=f,i.maxWidth=g)),d}:e.documentElement.currentStyle&&(bH=function(a,b){var c,d,e=a.currentStyle&&a.currentStyle[b],f=a.style;return e==null&&f&&f[b]&&(e=f[b]),bQ.test(e)&&!bM.test(b)&&(c=f.left,d=a.runtimeStyle&&a.runtimeStyle.left,d&&(a.runtimeStyle.left=a.currentStyle.left),f.left=b==="fontSize"?"1em":e,e=f.pixelLeft+"px",f.left=c,d&&(a.runtimeStyle.left=d)),e===""?"auto":e}),p.each(["height","width"],function(a,b){p.cssHooks[b]={get:function(a,c,d){if(c)return a.offsetWidth===0&&bN.test(bH(a,"display"))?p.swap(a,bT,function(){return cb(a,b,d)}):cb(a,b,d)},set:function(a,c,d){return b_(a,c,d?ca(a,b,d,p.support.boxSizing&&p.css(a,"boxSizing")==="border-box"):0)}}}),p.support.opacity||(p.cssHooks.opacity={get:function(a,b){return bL.test((b&&a.currentStyle?a.currentStyle.filter:a.style.filter)||"")?.01*parseFloat(RegExp.$1)+"":b?"1":""},set:function(a,b){var c=a.style,d=a.currentStyle,e=p.isNumeric(b)?"alpha(opacity="+b*100+")":"",f=d&&d.filter||c.filter||"";c.zoom=1;if(b>=1&&p.trim(f.replace(bK,""))===""&&c.removeAttribute){c.removeAttribute("filter");if(d&&!d.filter)return}c.filter=bK.test(f)?f.replace(bK,e):f+" "+e}}),p(function(){p.support.reliableMarginRight||(p.cssHooks.marginRight={get:function(a,b){return p.swap(a,{display:"inline-block"},function(){if(b)return bH(a,"marginRight")})}}),!p.support.pixelPosition&&p.fn.position&&p.each(["top","left"],function(a,b){p.cssHooks[b]={get:function(a,c){if(c){var d=bH(a,b);return bQ.test(d)?p(a).position()[b]+"px":d}}}})}),p.expr&&p.expr.filters&&(p.expr.filters.hidden=function(a){return a.offsetWidth===0&&a.offsetHeight===0||!p.support.reliableHiddenOffsets&&(a.style&&a.style.display||bH(a,"display"))==="none"},p.expr.filters.visible=function(a){return!p.expr.filters.hidden(a)}),p.each({margin:"",padding:"",border:"Width"},function(a,b){p.cssHooks[a+b]={expand:function(c){var d,e=typeof c=="string"?c.split(" "):[c],f={};for(d=0;d<4;d++)f[a+bV[d]+b]=e[d]||e[d-2]||e[0];return f}},bO.test(a)||(p.cssHooks[a+b].set=b_)});var cd=/%20/g,ce=/\[\]$/,cf=/\r?\n/g,cg=/^(?:color|date|datetime|datetime-local|email|hidden|month|number|password|range|search|tel|text|time|url|week)$/i,ch=/^(?:select|textarea)/i;p.fn.extend({serialize:function(){return p.param(this.serializeArray())},serializeArray:function(){return this.map(function(){return this.elements?p.makeArray(this.elements):this}).filter(function(){return this.name&&!this.disabled&&(this.checked||ch.test(this.nodeName)||cg.test(this.type))}).map(function(a,b){var c=p(this).val();return c==null?null:p.isArray(c)?p.map(c,function(a,c){return{name:b.name,value:a.replace(cf,"\r\n")}}):{name:b.name,value:c.replace(cf,"\r\n")}}).get()}}),p.param=function(a,c){var d,e=[],f=function(a,b){b=p.isFunction(b)?b():b==null?"":b,e[e.length]=encodeURIComponent(a)+"="+encodeURIComponent(b)};c===b&&(c=p.ajaxSettings&&p.ajaxSettings.traditional);if(p.isArray(a)||a.jquery&&!p.isPlainObject(a))p.each(a,function(){f(this.name,this.value)});else for(d in a)ci(d,a[d],c,f);return e.join("&").replace(cd,"+")};var cj,ck,cl=/#.*$/,cm=/^(.*?):[ \t]*([^\r\n]*)\r?$/mg,cn=/^(?:about|app|app\-storage|.+\-extension|file|res|widget):$/,co=/^(?:GET|HEAD)$/,cp=/^\/\//,cq=/\?/,cr=/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,cs=/([?&])_=[^&]*/,ct=/^([\w\+\.\-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/,cu=p.fn.load,cv={},cw={},cx=["*/"]+["*"];try{ck=f.href}catch(cy){ck=e.createElement("a"),ck.href="",ck=ck.href}cj=ct.exec(ck.toLowerCase())||[],p.fn.load=function(a,c,d){if(typeof a!="string"&&cu)return cu.apply(this,arguments);if(!this.length)return this;var e,f,g,h=this,i=a.indexOf(" ");return i>=0&&(e=a.slice(i,a.length),a=a.slice(0,i)),p.isFunction(c)?(d=c,c=b):c&&typeof c=="object"&&(f="POST"),p.ajax({url:a,type:f,dataType:"html",data:c,complete:function(a,b){d&&h.each(d,g||[a.responseText,b,a])}}).done(function(a){g=arguments,h.html(e?p("<div>").append(a.replace(cr,"")).find(e):a)}),this},p.each("ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split(" "),function(a,b){p.fn[b]=function(a){return this.on(b,a)}}),p.each(["get","post"],function(a,c){p[c]=function(a,d,e,f){return p.isFunction(d)&&(f=f||e,e=d,d=b),p.ajax({type:c,url:a,data:d,success:e,dataType:f})}}),p.extend({getScript:function(a,c){return p.get(a,b,c,"script")},getJSON:function(a,b,c){return p.get(a,b,c,"json")},ajaxSetup:function(a,b){return b?cB(a,p.ajaxSettings):(b=a,a=p.ajaxSettings),cB(a,b),a},ajaxSettings:{url:ck,isLocal:cn.test(cj[1]),global:!0,type:"GET",contentType:"application/x-www-form-urlencoded; charset=UTF-8",processData:!0,async:!0,accepts:{xml:"application/xml, text/xml",html:"text/html",text:"text/plain",json:"application/json, text/javascript","*":cx},contents:{xml:/xml/,html:/html/,json:/json/},responseFields:{xml:"responseXML",text:"responseText"},converters:{"* text":a.String,"text html":!0,"text json":p.parseJSON,"text xml":p.parseXML},flatOptions:{context:!0,url:!0}},ajaxPrefilter:cz(cv),ajaxTransport:cz(cw),ajax:function(a,c){function y(a,c,f,i){var k,s,t,u,w,y=c;if(v===2)return;v=2,h&&clearTimeout(h),g=b,e=i||"",x.readyState=a>0?4:0,f&&(u=cC(l,x,f));if(a>=200&&a<300||a===304)l.ifModified&&(w=x.getResponseHeader("Last-Modified"),w&&(p.lastModified[d]=w),w=x.getResponseHeader("Etag"),w&&(p.etag[d]=w)),a===304?(y="notmodified",k=!0):(k=cD(l,u),y=k.state,s=k.data,t=k.error,k=!t);else{t=y;if(!y||a)y="error",a<0&&(a=0)}x.status=a,x.statusText=(c||y)+"",k?o.resolveWith(m,[s,y,x]):o.rejectWith(m,[x,y,t]),x.statusCode(r),r=b,j&&n.trigger("ajax"+(k?"Success":"Error"),[x,l,k?s:t]),q.fireWith(m,[x,y]),j&&(n.trigger("ajaxComplete",[x,l]),--p.active||p.event.trigger("ajaxStop"))}typeof a=="object"&&(c=a,a=b),c=c||{};var d,e,f,g,h,i,j,k,l=p.ajaxSetup({},c),m=l.context||l,n=m!==l&&(m.nodeType||m instanceof p)?p(m):p.event,o=p.Deferred(),q=p.Callbacks("once memory"),r=l.statusCode||{},t={},u={},v=0,w="canceled",x={readyState:0,setRequestHeader:function(a,b){if(!v){var c=a.toLowerCase();a=u[c]=u[c]||a,t[a]=b}return this},getAllResponseHeaders:function(){return v===2?e:null},getResponseHeader:function(a){var c;if(v===2){if(!f){f={};while(c=cm.exec(e))f[c[1].toLowerCase()]=c[2]}c=f[a.toLowerCase()]}return c===b?null:c},overrideMimeType:function(a){return v||(l.mimeType=a),this},abort:function(a){return a=a||w,g&&g.abort(a),y(0,a),this}};o.promise(x),x.success=x.done,x.error=x.fail,x.complete=q.add,x.statusCode=function(a){if(a){var b;if(v<2)for(b in a)r[b]=[r[b],a[b]];else b=a[x.status],x.always(b)}return this},l.url=((a||l.url)+"").replace(cl,"").replace(cp,cj[1]+"//"),l.dataTypes=p.trim(l.dataType||"*").toLowerCase().split(s),l.crossDomain==null&&(i=ct.exec(l.url.toLowerCase())||!1,l.crossDomain=i&&i.join(":")+(i[3]?"":i[1]==="http:"?80:443)!==cj.join(":")+(cj[3]?"":cj[1]==="http:"?80:443)),l.data&&l.processData&&typeof l.data!="string"&&(l.data=p.param(l.data,l.traditional)),cA(cv,l,c,x);if(v===2)return x;j=l.global,l.type=l.type.toUpperCase(),l.hasContent=!co.test(l.type),j&&p.active++===0&&p.event.trigger("ajaxStart");if(!l.hasContent){l.data&&(l.url+=(cq.test(l.url)?"&":"?")+l.data,delete l.data),d=l.url;if(l.cache===!1){var z=p.now(),A=l.url.replace(cs,"$1_="+z);l.url=A+(A===l.url?(cq.test(l.url)?"&":"?")+"_="+z:"")}}(l.data&&l.hasContent&&l.contentType!==!1||c.contentType)&&x.setRequestHeader("Content-Type",l.contentType),l.ifModified&&(d=d||l.url,p.lastModified[d]&&x.setRequestHeader("If-Modified-Since",p.lastModified[d]),p.etag[d]&&x.setRequestHeader("If-None-Match",p.etag[d])),x.setRequestHeader("Accept",l.dataTypes[0]&&l.accepts[l.dataTypes[0]]?l.accepts[l.dataTypes[0]]+(l.dataTypes[0]!=="*"?", "+cx+"; q=0.01":""):l.accepts["*"]);for(k in l.headers)x.setRequestHeader(k,l.headers[k]);if(!l.beforeSend||l.beforeSend.call(m,x,l)!==!1&&v!==2){w="abort";for(k in{success:1,error:1,complete:1})x[k](l[k]);g=cA(cw,l,c,x);if(!g)y(-1,"No Transport");else{x.readyState=1,j&&n.trigger("ajaxSend",[x,l]),l.async&&l.timeout>0&&(h=setTimeout(function(){x.abort("timeout")},l.timeout));try{v=1,g.send(t,y)}catch(B){if(v<2)y(-1,B);else throw B}}return x}return x.abort()},active:0,lastModified:{},etag:{}});var cE=[],cF=/\?/,cG=/(=)\?(?=&|$)|\?\?/,cH=p.now();p.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var a=cE.pop()||p.expando+"_"+cH++;return this[a]=!0,a}}),p.ajaxPrefilter("json jsonp",function(c,d,e){var f,g,h,i=c.data,j=c.url,k=c.jsonp!==!1,l=k&&cG.test(j),m=k&&!l&&typeof i=="string"&&!(c.contentType||"").indexOf("application/x-www-form-urlencoded")&&cG.test(i);if(c.dataTypes[0]==="jsonp"||l||m)return f=c.jsonpCallback=p.isFunction(c.jsonpCallback)?c.jsonpCallback():c.jsonpCallback,g=a[f],l?c.url=j.replace(cG,"$1"+f):m?c.data=i.replace(cG,"$1"+f):k&&(c.url+=(cF.test(j)?"&":"?")+c.jsonp+"="+f),c.converters["script json"]=function(){return h||p.error(f+" was not called"),h[0]},c.dataTypes[0]="json",a[f]=function(){h=arguments},e.always(function(){a[f]=g,c[f]&&(c.jsonpCallback=d.jsonpCallback,cE.push(f)),h&&p.isFunction(g)&&g(h[0]),h=g=b}),"script"}),p.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/javascript|ecmascript/},converters:{"text script":function(a){return p.globalEval(a),a}}}),p.ajaxPrefilter("script",function(a){a.cache===b&&(a.cache=!1),a.crossDomain&&(a.type="GET",a.global=!1)}),p.ajaxTransport("script",function(a){if(a.crossDomain){var c,d=e.head||e.getElementsByTagName("head")[0]||e.documentElement;return{send:function(f,g){c=e.createElement("script"),c.async="async",a.scriptCharset&&(c.charset=a.scriptCharset),c.src=a.url,c.onload=c.onreadystatechange=function(a,e){if(e||!c.readyState||/loaded|complete/.test(c.readyState))c.onload=c.onreadystatechange=null,d&&c.parentNode&&d.removeChild(c),c=b,e||g(200,"success")},d.insertBefore(c,d.firstChild)},abort:function(){c&&c.onload(0,1)}}}});var cI,cJ=a.ActiveXObject?function(){for(var a in cI)cI[a](0,1)}:!1,cK=0;p.ajaxSettings.xhr=a.ActiveXObject?function(){return!this.isLocal&&cL()||cM()}:cL,function(a){p.extend(p.support,{ajax:!!a,cors:!!a&&"withCredentials"in a})}(p.ajaxSettings.xhr()),p.support.ajax&&p.ajaxTransport(function(c){if(!c.crossDomain||p.support.cors){var d;return{send:function(e,f){var g,h,i=c.xhr();c.username?i.open(c.type,c.url,c.async,c.username,c.password):i.open(c.type,c.url,c.async);if(c.xhrFields)for(h in c.xhrFields)i[h]=c.xhrFields[h];c.mimeType&&i.overrideMimeType&&i.overrideMimeType(c.mimeType),!c.crossDomain&&!e["X-Requested-With"]&&(e["X-Requested-With"]="XMLHttpRequest");try{for(h in e)i.setRequestHeader(h,e[h])}catch(j){}i.send(c.hasContent&&c.data||null),d=function(a,e){var h,j,k,l,m;try{if(d&&(e||i.readyState===4)){d=b,g&&(i.onreadystatechange=p.noop,cJ&&delete cI[g]);if(e)i.readyState!==4&&i.abort();else{h=i.status,k=i.getAllResponseHeaders(),l={},m=i.responseXML,m&&m.documentElement&&(l.xml=m);try{l.text=i.responseText}catch(a){}try{j=i.statusText}catch(n){j=""}!h&&c.isLocal&&!c.crossDomain?h=l.text?200:404:h===1223&&(h=204)}}}catch(o){e||f(-1,o)}l&&f(h,j,l,k)},c.async?i.readyState===4?setTimeout(d,0):(g=++cK,cJ&&(cI||(cI={},p(a).unload(cJ)),cI[g]=d),i.onreadystatechange=d):d()},abort:function(){d&&d(0,1)}}}});var cN,cO,cP=/^(?:toggle|show|hide)$/,cQ=new RegExp("^(?:([-+])=|)("+q+")([a-z%]*)$","i"),cR=/queueHooks$/,cS=[cY],cT={"*":[function(a,b){var c,d,e=this.createTween(a,b),f=cQ.exec(b),g=e.cur(),h=+g||0,i=1,j=20;if(f){c=+f[2],d=f[3]||(p.cssNumber[a]?"":"px");if(d!=="px"&&h){h=p.css(e.elem,a,!0)||c||1;do i=i||".5",h=h/i,p.style(e.elem,a,h+d);while(i!==(i=e.cur()/g)&&i!==1&&--j)}e.unit=d,e.start=h,e.end=f[1]?h+(f[1]+1)*c:c}return e}]};p.Animation=p.extend(cW,{tweener:function(a,b){p.isFunction(a)?(b=a,a=["*"]):a=a.split(" ");var c,d=0,e=a.length;for(;d<e;d++)c=a[d],cT[c]=cT[c]||[],cT[c].unshift(b)},prefilter:function(a,b){b?cS.unshift(a):cS.push(a)}}),p.Tween=cZ,cZ.prototype={constructor:cZ,init:function(a,b,c,d,e,f){this.elem=a,this.prop=c,this.easing=e||"swing",this.options=b,this.start=this.now=this.cur(),this.end=d,this.unit=f||(p.cssNumber[c]?"":"px")},cur:function(){var a=cZ.propHooks[this.prop];return a&&a.get?a.get(this):cZ.propHooks._default.get(this)},run:function(a){var b,c=cZ.propHooks[this.prop];return this.options.duration?this.pos=b=p.easing[this.easing](a,this.options.duration*a,0,1,this.options.duration):this.pos=b=a,this.now=(this.end-this.start)*b+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),c&&c.set?c.set(this):cZ.propHooks._default.set(this),this}},cZ.prototype.init.prototype=cZ.prototype,cZ.propHooks={_default:{get:function(a){var b;return a.elem[a.prop]==null||!!a.elem.style&&a.elem.style[a.prop]!=null?(b=p.css(a.elem,a.prop,!1,""),!b||b==="auto"?0:b):a.elem[a.prop]},set:function(a){p.fx.step[a.prop]?p.fx.step[a.prop](a):a.elem.style&&(a.elem.style[p.cssProps[a.prop]]!=null||p.cssHooks[a.prop])?p.style(a.elem,a.prop,a.now+a.unit):a.elem[a.prop]=a.now}}},cZ.propHooks.scrollTop=cZ.propHooks.scrollLeft={set:function(a){a.elem.nodeType&&a.elem.parentNode&&(a.elem[a.prop]=a.now)}},p.each(["toggle","show","hide"],function(a,b){var c=p.fn[b];p.fn[b]=function(d,e,f){return d==null||typeof d=="boolean"||!a&&p.isFunction(d)&&p.isFunction(e)?c.apply(this,arguments):this.animate(c$(b,!0),d,e,f)}}),p.fn.extend({fadeTo:function(a,b,c,d){return this.filter(bZ).css("opacity",0).show().end().animate({opacity:b},a,c,d)},animate:function(a,b,c,d){var e=p.isEmptyObject(a),f=p.speed(b,c,d),g=function(){var b=cW(this,p.extend({},a),f);e&&b.stop(!0)};return e||f.queue===!1?this.each(g):this.queue(f.queue,g)},stop:function(a,c,d){var e=function(a){var b=a.stop;delete a.stop,b(d)};return typeof a!="string"&&(d=c,c=a,a=b),c&&a!==!1&&this.queue(a||"fx",[]),this.each(function(){var b=!0,c=a!=null&&a+"queueHooks",f=p.timers,g=p._data(this);if(c)g[c]&&g[c].stop&&e(g[c]);else for(c in g)g[c]&&g[c].stop&&cR.test(c)&&e(g[c]);for(c=f.length;c--;)f[c].elem===this&&(a==null||f[c].queue===a)&&(f[c].anim.stop(d),b=!1,f.splice(c,1));(b||!d)&&p.dequeue(this,a)})}}),p.each({slideDown:c$("show"),slideUp:c$("hide"),slideToggle:c$("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(a,b){p.fn[a]=function(a,c,d){return this.animate(b,a,c,d)}}),p.speed=function(a,b,c){var d=a&&typeof a=="object"?p.extend({},a):{complete:c||!c&&b||p.isFunction(a)&&a,duration:a,easing:c&&b||b&&!p.isFunction(b)&&b};d.duration=p.fx.off?0:typeof d.duration=="number"?d.duration:d.duration in p.fx.speeds?p.fx.speeds[d.duration]:p.fx.speeds._default;if(d.queue==null||d.queue===!0)d.queue="fx";return d.old=d.complete,d.complete=function(){p.isFunction(d.old)&&d.old.call(this),d.queue&&p.dequeue(this,d.queue)},d},p.easing={linear:function(a){return a},swing:function(a){return.5-Math.cos(a*Math.PI)/2}},p.timers=[],p.fx=cZ.prototype.init,p.fx.tick=function(){var a,b=p.timers,c=0;for(;c<b.length;c++)a=b[c],!a()&&b[c]===a&&b.splice(c--,1);b.length||p.fx.stop()},p.fx.timer=function(a){a()&&p.timers.push(a)&&!cO&&(cO=setInterval(p.fx.tick,p.fx.interval))},p.fx.interval=13,p.fx.stop=function(){clearInterval(cO),cO=null},p.fx.speeds={slow:600,fast:200,_default:400},p.fx.step={},p.expr&&p.expr.filters&&(p.expr.filters.animated=function(a){return p.grep(p.timers,function(b){return a===b.elem}).length});var c_=/^(?:body|html)$/i;p.fn.offset=function(a){if(arguments.length)return a===b?this:this.each(function(b){p.offset.setOffset(this,a,b)});var c,d,e,f,g,h,i,j={top:0,left:0},k=this[0],l=k&&k.ownerDocument;if(!l)return;return(d=l.body)===k?p.offset.bodyOffset(k):(c=l.documentElement,p.contains(c,k)?(typeof k.getBoundingClientRect!="undefined"&&(j=k.getBoundingClientRect()),e=da(l),f=c.clientTop||d.clientTop||0,g=c.clientLeft||d.clientLeft||0,h=e.pageYOffset||c.scrollTop,i=e.pageXOffset||c.scrollLeft,{top:j.top+h-f,left:j.left+i-g}):j)},p.offset={bodyOffset:function(a){var b=a.offsetTop,c=a.offsetLeft;return p.support.doesNotIncludeMarginInBodyOffset&&(b+=parseFloat(p.css(a,"marginTop"))||0,c+=parseFloat(p.css(a,"marginLeft"))||0),{top:b,left:c}},setOffset:function(a,b,c){var d=p.css(a,"position");d==="static"&&(a.style.position="relative");var e=p(a),f=e.offset(),g=p.css(a,"top"),h=p.css(a,"left"),i=(d==="absolute"||d==="fixed")&&p.inArray("auto",[g,h])>-1,j={},k={},l,m;i?(k=e.position(),l=k.top,m=k.left):(l=parseFloat(g)||0,m=parseFloat(h)||0),p.isFunction(b)&&(b=b.call(a,c,f)),b.top!=null&&(j.top=b.top-f.top+l),b.left!=null&&(j.left=b.left-f.left+m),"using"in b?b.using.call(a,j):e.css(j)}},p.fn.extend({position:function(){if(!this[0])return;var a=this[0],b=this.offsetParent(),c=this.offset(),d=c_.test(b[0].nodeName)?{top:0,left:0}:b.offset();return c.top-=parseFloat(p.css(a,"marginTop"))||0,c.left-=parseFloat(p.css(a,"marginLeft"))||0,d.top+=parseFloat(p.css(b[0],"borderTopWidth"))||0,d.left+=parseFloat(p.css(b[0],"borderLeftWidth"))||0,{top:c.top-d.top,left:c.left-d.left}},offsetParent:function(){return this.map(function(){var a=this.offsetParent||e.body;while(a&&!c_.test(a.nodeName)&&p.css(a,"position")==="static")a=a.offsetParent;return a||e.body})}}),p.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(a,c){var d=/Y/.test(c);p.fn[a]=function(e){return p.access(this,function(a,e,f){var g=da(a);if(f===b)return g?c in g?g[c]:g.document.documentElement[e]:a[e];g?g.scrollTo(d?p(g).scrollLeft():f,d?f:p(g).scrollTop()):a[e]=f},a,e,arguments.length,null)}}),p.each({Height:"height",Width:"width"},function(a,c){p.each({padding:"inner"+a,content:c,"":"outer"+a},function(d,e){p.fn[e]=function(e,f){var g=arguments.length&&(d||typeof e!="boolean"),h=d||(e===!0||f===!0?"margin":"border");return p.access(this,function(c,d,e){var f;return p.isWindow(c)?c.document.documentElement["client"+a]:c.nodeType===9?(f=c.documentElement,Math.max(c.body["scroll"+a],f["scroll"+a],c.body["offset"+a],f["offset"+a],f["client"+a])):e===b?p.css(c,d,e,h):p.style(c,d,e,h)},c,g?e:b,g,null)}})}),a.jQuery=a.$=p,typeof define=="function"&&define.amd&&define.amd.jQuery&&define("jquery",[],function(){return p})})(window);var DIGITS1 = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
var DIGITS2 = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';

function zakoduj(n)
{
 if (n<52) return DIGITS1[n];
 if (n>=672) return '_'+n+'_';
 n -= 52; // 0 <= n < 620
 return '' + Math.floor(n/62) + DIGITS2[n%62]; 
}

function rozkoduj(a)
{
 var l = a.length;
 if (l==1)
 {
  for (var i=0; i<52; i++) if (DIGITS1[i]==a) return i;
  alert('error 1: a='+a); return -1;
 }
 if (l==2)
 {
  var x = a.charAt(0), y = a.charAt(1), z=-1;
  for (var i=0; i<62; i++) if (DIGITS2[i]==y) z = i;
  if (z==-1) { alert('error 2: a='+a); return -2; }
  if (!(x>=0 && x<10)) { alert('error 3: a='+a); return -3; }
  return 52 + 62*x + z; 
 }
 var z = parseInt(a.substr(1));
 if (z == NaN) { alert('error 4: a='+a); return -4; }
 return z;
}

function parsujzakodowanywariant(s)
{
 var ret = [];
 var i = 0, l = s.length;
 while(i<l)
 {
  var a = s.charAt(i);
  if (a=='_')
  {
   var j = i+1;
   while(s.charAt(j)!='_') j++;
   ret.push( s.substring(i,j+1) );
   i = j+1;
  }
  else if (DIGITS1.indexOf(a)>-1)
  {
   ret.push(a);
   i++;
  }
  else
  {
   ret.push(a+s.charAt(i+1));
   i+=2;
  }
 }
 return ret;
}

function parsujzakodowanewarianty(s)
{
 var ar = s.split('-');
 var ret = [];
 for (var i=0; i<ar.length; i++) ret.push( parsujzakodowanywariant(ar[i]) );
 return ret;
}

function zakodujwarianty(ar)
{
 var ret = '';
 for (var i=0; i<ar.length; i++)
 {
  var wariant = ar[i];
  for (var j=0; j<wariant.length; j++)
   ret += zakoduj(wariant[j]);
  if (i+1 < ar.length ) ret += '-';
 }
 return ret;
}

function rozkodujwarianty(ar)
{
 var ret = [];
 for (var i=0; i<ar.length; i++)
 {
  var retline = [];
  var wariant = ar[i];
  for (var j=0; j<wariant.length; j++)
   retline.push( rozkoduj(wariant[j]) );
  ret.push( retline );
 }
 return ret;
}

function isEditor()
{
	return (document.getElementById('edytorek')) ? true : false;
}

function insert_puzzle_css()
{
 var st = document.createElement('style');
 var a = ''
 a += 'body { font-family: Verdana; background: white; color: black; }';
 a += '#puzzlediv h1 { font-size: x-large; text-align: left; }';
 a += '#puzzlediv fieldset { border: none; display: inline; vertical-align: top; padding:0; margin:0; }';
 a += '#puzzlediv h1,h2,h3,h4,h5,h6 { margin-top: 0; }';
 a += 'ol.linelist { counter-reset: list; padding-left: 0; }';
 a += 'ol.linelist > li { list-style: none; position: relative; margin-left: 3em; }';
 a += 'ol.linelist > li:before { counter-increment: list; content: counter(list, decimal) ") "; position: absolute; left: -2.5em; }';
 a += 'table.board { border:0; padding:0; border-collapse:collapse; border-spacing:0;  }';
 a += 'table.board td { text-align:center; padding:0; border:0px solid black; }';
 a += '#promotionDialog fieldset:hover { background: yellow; }';
 a += '#h1header .enh { font-size: normal; font-weight: bold; }';
 a += '#h1header h3 { font-size: 100%; color: black; margin-bottom: 0.3em; margin-top: 0.3em; } ';
 a += '#h1header h5 { font-size: 70%; color: #555; margin-bottom: 0.1em; margin-top: 0.1em; margin: 0; } ';
 a += '#h1header h6 { font-size: 70%; color: #555; margin-bottom: 0.1em; margin-top: 0.1em; margin: 0; } ';
 a += '#h1header h5 span.chesthetica { font-family: "Courier New", Courier, monospace; } ';
 a += '#beforepuzzlediv { border-bottom: 1px solid #ddd; } ';
 a += '.removetaphighlight { -webkit-tap-highlight-color: transparent; } ';
 a += 'button { background-image: linear-gradient(to top, #e4e4e4, #f7f7f7);  } ';
 a += 'button:hover { background-image: linear-gradient(to bottom, #e4e4e4, #f7f7f7);  } ';
 a += '@keyframes shadow-pulse { 0% { box-shadow: inset 0 0 0 0px rgba(0, 0, 0, 0.2); } 100% { box-shadow: inset 0 0 0 20px rgba(0, 0, 0, 0); } } ';
 a += '.nextlinebutton { animation: shadow-pulse 1s 7; background: #ffffcc; background-image: linear-gradient(to right, #ffffee, #ffffcc); border-radius: 7px; } ';
 a += '.nextpuzzlebutton { animation: shadow-pulse 2s 4; background: #ffffcc; background-image: linear-gradient(to right, #ffffee, #ffffcc); border-radius: 7px; } ';
 a += '.limitOKbutton { animation: shadow-pulse 1s 7; background: #ffffcc; background-image: linear-gradient(to right, #ffffee, #ffffcc); border-radius: 7px; } ';
 a += '.fullscreenbbbutton { animation: shadow-pulse 0.7s 3; border-radius: 7px; } ';
 a += '@keyframes spin { 100% { transform: rotate(360deg); } } ';
 a += '.spinning { animation: spin 2s linear infinite; } ';

 st.innerHTML = a;
 document.head.appendChild(st);	
}

function styleh1header(a)
{
	var a = a || el('h1header');
	//a.style.margin = '0 0 0.5em 0';
	a.style.fontWeight = 'bold';
	var h3 = a.querySelectorAll('h3');
	var ile = h3.length;
	if (ile) for (var i=0; i < ile; i++)
	{
		h3[i].style.fontSize = '100%';
		h3[i].style.color = '#000000';
		h3[i].style.margin = '0.3em 0 0.3em 0';
	}
	var h5 = a.querySelectorAll('h5');
	var ile = h5.length;
	if (ile) for (var i=0; i < ile; i++)
	{
		h5[i].style.fontSize = '70%';
		h5[i].style.color = '#555';
		h5[i].style.margin = '0';
	}
}

function matelines(ile)
{
 if (ile == 0) return [];
 var moves = GenerateValidMoves();
 var n = moves.length;
 if (n == 0) return [];
 var ret = [];
 for (var i=0; i<n; i++)
 {
  var move = moves[i];
  MakeMove(move);
  var replies = GenerateValidMoves();
  var m = replies.length;
  if (m == 0)
  {
   if (g_inCheck) ret.push( [moves[i]] ); 
  }
  else
  {
   var winning = true;
   var further = new Array(m);
   for (var j=0; j<m && winning; j++)
   {
    var reply = replies[j];
    MakeMove(reply);
    further[j] = matelines(ile-1);
    if (further[j].length == 0) winning = false;
    UnmakeMove(reply);
   }
   if (winning)
   {
    for (var j=0; j<m; j++)
    {
     var ini = [moves[i],replies[j]];
     var k = further[j].length;
     for (var l=0; l<k; l++) ret.push( ini.concat(further[j][l]) );
    } 
   }
  }
  UnmakeMove(move);
 }
 return ret;
}

function moveindex(move)
{
 var n=0, moves = GenerateValidMoves();
 for (var i=0; i<moves.length; i++) if (move > moves[i]) n++;
 return n;
}

function indextomove(i)
{
 var SortedValidMoves = GenerateValidMoves().sort(function(a,b){return a-b});
 SortedValidMoves.push(NULLMOVE); 
 return SortedValidMoves[i];
}

function indexlines(lines)
{
 if (lines.length == 0) return [];
 var ret = [];
 for (var i=0; i<lines.length; i++)
 {
  var line = [];
  var stack = [];
  for (var j=0; j<lines[i].length; j++)
  {
   var move = lines[i][j];
   line.push(moveindex(move));
   MakeMove(move);
   stack.push(move);
  }
  ret.push(line);
  for (var j=lines[i].length-1; j>=0; j--) UnmakeMove(stack.pop());
 }
 return ret;
}


function fullmovenumber(fen)
{
 return Number( fen.split(' ')[5] );
}

function ponumeruj(line,blackstarts)
{
 if (line == '') return '';
 var ruchy = line.split(' ');
 var moves = '';
 var halfmoveindex = 0;
 var movenumber = fullmovenumber(fen);
 if (blackstarts)
 {
  moves = '' + movenumber + '...' + ruchy[0] + ' ';
  halfmoveindex = 1;
  movenumber++;
 }
 while (true)
 {
  if (halfmoveindex+1 < ruchy.length)
  {
   moves += movenumber + '.' + ruchy[halfmoveindex] + ' ';
   halfmoveindex++;
   if (halfmoveindex+1 < ruchy.length)
   {
    moves += ruchy[halfmoveindex] + ' ';
    halfmoveindex++;
    movenumber++;
   }
   else
   {
    return moves;
   }
  }
  else
  {
   return moves;
  }
 }
}

function ponumerujarray(line,blackstarts,linebreak)
{
 if (linebreak == null) linebreak = '<br>';
 var ruchy = line;
 var moves = '';
 var halfmoveindex = 0;
 var movenumber = fullmovenumber(fen);
 var chars = 0;
 if (blackstarts)
 {
  moves = '' + movenumber + '...' + ruchy[0] + ' ';
  halfmoveindex = 1;
  movenumber++;
 }
 while (true)
 {
  if (halfmoveindex+1 <= ruchy.length)
  {
   var nowyruch = movenumber + '.' + ruchy[halfmoveindex] + ' '; 
   moves += nowyruch;
   chars += nowyruch.length;
   halfmoveindex++;
   if (halfmoveindex+1 <= ruchy.length)
   {
    nowyruch = ruchy[halfmoveindex] + ' '; 
    moves += nowyruch;
    chars += nowyruch.length;
    if (chars >= 20 && halfmoveindex+1<ruchy.length)
      { moves += linebreak; chars = 0; }
    halfmoveindex++;
    movenumber++;
   }
   else
   {
    return moves;
   }
  }
  else
  {
   return moves;
  }
 }
}


function SANindexlines(lines,blackstarts) // deprecated; use totalperceive below
{
 if (lines.length == 0) return [];
 var ret = [];
 for (var i=0; i<lines.length; i++)
 {
  var line = '';
  var stack = [];
  for (var j=0; j<lines[i].length; j++)
  {
   var move = indextomove( lines[i][j] );
   line += GetMoveSAN(move) + ' ';
   MakeMove(move);
   stack.push(move);
  }
  ret.push( ponumeruj(line,blackstarts) );
  for (var j=lines[i].length-1; j>=0; j--) UnmakeMove(stack.pop());
 }
 return ret;
}

function totalperceive(lines,blackstarts) // accepts lines of indices, returns: ponumerujlines, garbomoves, FENs, SANs
{
 if (lines.length == 0) return [];
 var ret = [], garbomoves = [], FENs = [], SANs = [];
 for (var i=0; i<lines.length; i++)
 {
  var line = '';
  var stack = [];
  var sanarray = [], fenarray = [], garboray = [];
  for (var j=0; j<lines[i].length; j++)
  {
   var move = indextomove( lines[i][j] );
   var san = GetMoveSAN(move);
   sanarray.push(san);
   line += san + ' ';
   MakeMove(move);
   stack.push(move);
   fenarray.push(GetFen());
   garboray.push(move);
  }
  ret.push( ponumeruj(line,blackstarts) );
  SANs.push(sanarray); FENs.push(fenarray); garbomoves.push(garboray);
  for (var j=lines[i].length-1; j>=0; j--) UnmakeMove(stack.pop());
 }
 return [ret,garbomoves,FENs,SANs];
}

function symmetrizeSANleftright(san)
{
	var a = san.replace(/a/g,'A');
	a = a.replace(/b/g,'S');	a = a.replace(/c/g,'C');	a = a.replace(/d/g,'D');	a = a.replace(/e/g,'E');
	a = a.replace(/f/g,'F');	a = a.replace(/g/g,'G');	a = a.replace(/h/g,'H');	a = a.replace(/A/g,'h');
	a = a.replace(/S/g,'g');	a = a.replace(/C/g,'f');	a = a.replace(/D/g,'e');	a = a.replace(/E/g,'d');
	a = a.replace(/F/g,'c');	a = a.replace(/G/g,'b');	a = a.replace(/H/g,'a');
	return a;
}

function symmetrizeFENleftright(fen)
{
	var fenparts = fen.split(' ');
	var rows = fenparts[0].split('/');
	var newhead = [];
	for (var i=0; i<8; i++) newhead.push(rows[i].split("").reverse().join(""));
	newhead = newhead.join('/');
	return newhead + ' ' + fenparts[1] + ' - ' + symmetrizeSANleftright(fenparts[3]) + ' ' + fenparts[4] + ' ' + fenparts[5];
}

function symmetrizeOKLINESleftright(lines)
{
	var ile = lines.length; if (ile == 0) return [];
	var newlines = [];
	for (var i=0; i < ile; i++)
		newlines.push(symmetrizeSANleftright(lines[i]));
	return newlines;
}

function symmetrizeFENSleftright(FENs)
{
	var ile = FENs.length; if (ile == 0) return [];
	var newFENs = [];
	for (var i=0; i < ile; i++)
	{
		var fenarray = [], len = FENs[i].length;
		for (var x=0; x < len; x++)
			fenarray.push(symmetrizeFENleftright(FENs[i][x]));
		newFENs.push(fenarray);
	}
	return newFENs;
}

function symmetrizePUZZLEleftright()
{
 if (fen.split(' ')[2] != '-') return; // castling is available
 fen = symmetrizeFENleftright(fen);
 zadanie[0] = fen;
 zadanie[1] = symmetrizeOKLINESleftright(zadanie[1]);
 //zadanie[7] = total[3]; // SANs
 zadanie[8] = symmetrizeFENSleftright(zadanie[8]);
 //zadanie[9] = total[1]; // garbomoves
 //console.log('zadanie symmetrized: left-right');
}

function symmetrizeSANbottomtop(san)
{
	var a = san.replace(/1/g,'J');
	a = a.replace(/2/g,'W');
	a = a.replace(/3/g,'T');
	a = a.replace(/4/g,'V');
	a = a.replace(/5/g,'I');
	a = a.replace(/6/g,'X');
	a = a.replace(/7/g,'E');
	a = a.replace(/8/g,'H');
	a = a.replace(/J/g,'8');
	a = a.replace(/W/g,'7');
	a = a.replace(/T/g,'6');
	a = a.replace(/V/g,'5');
	a = a.replace(/I/g,'4');
	a = a.replace(/X/g,'3');
	a = a.replace(/E/g,'2');
	a = a.replace(/H/g,'1');
	return a;
}

function symmetrizeOKLINESbottomtop(lines)
{
	var ile = lines.length; if (ile == 0) return [];
	var newlines = [];
	for (var i=0; i < ile; i++)
		newlines.push(symmetrizeSANbottomtop(lines[i]));
	return newlines;
}

function symmetrizeFENbottomtop(fen)
{
	var fenparts = fen.split(' ');
	fenparts[0] = fenparts[0].split('/').reverse().join('/');
	return fenparts.join(' ');
}

function symmetrizeFENSbottomtop(FENs)
{
	var ile = FENs.length; if (ile == 0) return [];
	var newFENs = [];
	for (var i=0; i < ile; i++)
	{
		var fenarray = [], len = FENs[i].length;
		for (var x=0; x < len; x++)
			fenarray.push(symmetrizeFENbottomtop(FENs[i][x]));
		newFENs.push(fenarray);
	}
	return newFENs;
}

function symmetrizePUZZLEbottomtop()
{
 if (fen.split(' ')[2] != '-') return; // castling is available
 if (fen.toLowerCase().indexOf('p')>-1) return // pawns are present
 fen = symmetrizeFENbottomtop(fen);
 zadanie[0] = fen;
 zadanie[1] = symmetrizeOKLINESbottomtop(zadanie[1]);
 //zadanie[7] = total[3]; // SANs
 zadanie[8] = symmetrizeFENSbottomtop(zadanie[8]);
 //zadanie[9] = total[1]; // garbomoves
 //console.log('zadanie symmetrized: bottom-top');
}

function invertcase(str)
{
	var a = str.replace(/K/g,'J');
	a = a.replace(/Q/g,'W');	a = a.replace(/R/g,'E');	a = a.replace(/B/g,'V');	a = a.replace(/N/g,'M');
	a = a.replace(/P/g,'O');	a = a.replace(/k/g,'j');	a = a.replace(/q/g,'u');	a = a.replace(/r/g,'s');
	a = a.replace(/b/g,'v');	a = a.replace(/n/g,'m');	a = a.replace(/p/g,'o');	a = a.replace(/J/g,'k');
	a = a.replace(/W/g,'q');	a = a.replace(/E/g,'r');	a = a.replace(/V/g,'b');	a = a.replace(/M/g,'n');
	a = a.replace(/O/g,'p');	a = a.replace(/j/g,'K');	a = a.replace(/u/g,'Q');	a = a.replace(/s/g,'R');
	a = a.replace(/v/g,'B');	a = a.replace(/m/g,'N');	a = a.replace(/o/g,'P');
	a = a.replace(/T/g,'L');	a = a.replace(/t/g,'T');	a = a.replace(/L/g,'t');
	return a;
}

function symmetrizeFENcolors(fen)
{
	var tomove = (fen.split(' ')[1] == 'w') ? ' b ' : ' w ';
	var a = invertcase(fen);
	var parts = a.split(' ');
	return parts[0] + tomove + parts[2] + ' ' + symmetrizeSANbottomtop(parts[3]).toLowerCase() + ' ' + parts[4] + ' ' + parts[5];
}

function symmetrizeFENScolors(FENs)
{
	var ile = FENs.length; if (ile == 0) return [];
	var newFENs = [];
	for (var i=0; i < ile; i++)
	{
		var fenarray = [], len = FENs[i].length;
		for (var x=0; x < len; x++)
			fenarray.push(symmetrizeFENcolors(FENs[i][x]));
		newFENs.push(fenarray);
	}
	return newFENs;
}

function symmetrizeOKLINEScolors(lines,blackstarts)
{
	var ile = lines.length; if (ile == 0) return [];
	var newlines = [];
	for (var i=0; i < ile; i++)
	{
		var line = lines[i];
		line = line.replace(/\d+\.+/,'');
		newlines.push(ponumeruj(line,blackstarts));
	}
	return newlines;
}

function swap_colors_in_header()
{
	function swap(s,a,b)
	{
		var rexa = eval('/'+a+'/g'); var rexb = eval('/'+b+'/g');
		s = s.replace(rexa,'tgdytegf5');
		s = s.replace(rexb,a);
		s = s.replace(/tgdytegf5/g,b);
		return s;
	}
	var h = el('h1header').innerHTML;
	h = swap(h,'White','Black');
	h = swap(h,'white','black');
	h = swap(h,'Biał','Czarn');
	h = swap(h,'biał','czarn');
	//h = swap(h,'Белы','Черны');
	//h = swap(h,'белы','черны');
	h = swap(h,'Белы','Чёрны');
	h = swap(h,'белы','чёрны');
	el('h1header').innerHTML = h;
}

function symmetrizePUZZLEcolors()
{
 if (fen.split(' ')[2] != '-') return; // castling is available
 if (fen.toLowerCase().indexOf('p')>-1) return // pawns are present
 fen = symmetrizeFENcolors(fen);
 zadanie[0] = fen;
 zadanie[1] = symmetrizeOKLINEScolors(zadanie[1]);
 //zadanie[7] = total[3]; // SANs
 zadanie[8] = symmetrizeFENScolors(zadanie[8]);
 //zadanie[9] = total[1]; // garbomoves
 niema = invertcase(niema);
 swap_colors_in_header();
 //console.log('zadanie symmetrized: colors');
}

function symmetrizePUZZLEbottomtopcolors()
{
	fen = symmetrizeFENbottomtop(fen);
	fen = symmetrizeFENcolors(fen);
	zadanie[0] = fen;
	zadanie[1] = symmetrizeOKLINESbottomtop(zadanie[1]);
	var blackstarts = fen.indexOf(' b ')>-1;
	zadanie[1] = symmetrizeOKLINEScolors(zadanie[1],blackstarts);
	//zadanie[7] = total[3]; // SANs
	zadanie[8] = symmetrizeFENSbottomtop(zadanie[8]);
	zadanie[8] = symmetrizeFENScolors(zadanie[8]);
	//zadanie[9] = total[1]; // garbomoves
	niema = invertcase(niema);
	swap_colors_in_header();
	//console.log('zadanie symmetrized: bottom-top colors');
}

function symmetrizeh1header()
{
	h1header.style.position = 'relative';
	var div = newel('div'); div.style.position = 'absolute'; div.style.left = '0'; div.style.right = '0'; div.style.bottom = '0'; div.style.top = '0';
	h1header.appendChild(div);
	div.style.backgroundImage = 'url('+kostka(30).src+')';
	div.style.backgroundRepeat = 'repeat-x';
	div.style.backgroundSize = 'contain';
	div.style.opacity = '0.1';
	div.style.zIndex = '-1';	 
}


function symmetrizePUZZLE()
{
	function truefalse() { return Math.random()<0.5; }
	if (truefalse()) { flipchessboard(); /*console.log('zadanie symmetrized: flip');*/ }
	if (truefalse()) symmetrizePUZZLEleftright();
	if (truefalse()) symmetrizePUZZLEbottomtop();
	if (truefalse()) symmetrizePUZZLEcolors();
	if (truefalse()) symmetrizePUZZLEbottomtopcolors();
}

// ----------------------------------------

function subset(a,b)
{
 for (var i=0; i<a.length; i++)
 {
  var contains = false;
  for (var j=0; j<b.length; j++)
   if (a[i] == b[j]) contains = true;
  if (contains == false) return false;
 } 
 return true;
}

function applyrules(rules,ids)
{
 if (document.getElementById('altbox').checked) return ids;
 if (rules.length == 0) return ids;
 var jeszczeraz = true;
 while(jeszczeraz)
 {
  jeszczeraz = false;
  for (var i=0; i<rules.length; i++)
  {
   var a = rules[i][0];
   var b = rules[i][1]; 
   if (subset(a,ids) && !subset(b,ids) )
   {
    jeszczeraz = true;
    ids = ids.concat(b);
   }
   else if (subset(b,ids) && !subset(a,ids))
   {
    jeszczeraz = true;
    ids = ids.concat(a);
   }
  }
 } 
 return ids;
}

// -----------------------------------------------

function wyparsuj(lancuch,wyrazenie)
{
 var rezu = wyrazenie.exec(lancuch);
 if (!rezu) return "";
 return rezu[0] + '_' + wyparsuj(lancuch.replace(rezu[0],""),wyrazenie);
}

function tylkoruchy(a)
{
 //var ru = /(([KQRBNP]{0,1}([a-zA-Z]{0,1}\d{0,2}){0,1}[-x]{0,1}[a-zA-Z]\d{1,2}(={0,1}[QRBN]){0,1})|(O-O(-O){0,1}))[\+#]{0,1}/;
 var ru = /--|((([KQRBNP]{0,1}([a-zA-Z]{0,1}\d{0,2}){0,1}[-x]{0,1}[a-zA-Z]\d{1,2}(={0,1}[QRBN]){0,1})|(O-O(-O){0,1}))[\+#]{0,1})/;
 var moves = wyparsuj(a,ru) + "koniec";
 moves = moves.replace("_koniec","");
 moves = moves.replace("koniec","");
 return moves;
}

function sameruchyarray(a)
{
 var b = tylkoruchy(a);
 if (b == '') return [];
 return b.split('_');
}


function solutiondepth(oklines)
{
	var ile = oklines.length;
	if (ile == 0) return 0;
	var max = 0;
	for (var i=0; i < ile; i++)
	{
		var d = oklines[i].split(' ').length / 2;
		if (d > max) max = d;
	}
	return max;
}

function puzzleindexdocumentready()
{
 g_timeout = parseInt(TimePerMove.value); //g_timeout = 1000;
 UINewGame();
 document.getElementById('FenTextBox').value = fen;
 UIChangeFEN();
 
 var warianty = rozkodujwarianty(parsujzakodowanewarianty(encodedlines));
 if (MateIn == 1)
 {
	 warianty = indexlines(matelines(1));
	 if (warianty.length == 0) alert('But there is no mate in 1.');
	 encodedlines = zakodujwarianty(warianty);
	 successrule = '';
	 pozadaniu = false;
	 altrules = findrules(parsujzakodowanewarianty(encodedlines));
	 matein = 1;
	 if (ismobile()) el('prepuzzle').style.minHeight = '45px';
 }
 if (MateIn == 2)
 {
	 warianty = smartmate2();
	 encodedlines = zakodujwarianty(warianty);
	 successrule = '';
	 pozadaniu = false;
	 altrules = findrules(parsujzakodowanewarianty(encodedlines));
	 matein = 2;
	 if (ismobile()) el('prepuzzle').style.minHeight = '45px';
 }
 
 var blackstarts = fen.indexOf(' b ') > -1;
 var total = totalperceive(warianty,blackstarts);
 var oklines = total[0];
 if (encodedlines == '') oklines = [];
 zadanie = new Array();
 zadanie[0] = fen;
 zadanie[1] = oklines;
 zadanie[2] = [];
 var depth = solutiondepth(oklines);
 if (0 < matein && matein < depth)
 {
	 console.log('Move limit N='+matein+' is shorter than the solution depth. Corrected to N=0.');
	 matein = 0; // evidently the puzzle author has mistakenly specified the move limit
 }
 if (0 < depth && depth < matein)
 {
	 console.log('Move limit N='+matein+' is longer than the nonzero solution depth. Corrected to N=0.');
	 matein = 0;
 }
 zadanie[3] = matein;
 zadanie[4] = altrules;
 zadanie[5] = flipcyfra=='1';
 zadanie[6] = new Array(); // array of indices of redundant oklines
 zadanie[7] = total[3]; // SANs
 zadanie[8] = total[2]; // FENs
 zadanie[9] = total[1]; // garbomoves
 
 if (fen == 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1') randomsymmetry = false;
 if (randomsymmetry) symmetrizePUZZLE();
 galogg('puzzle-symmetry', (randomsymmetry) ? 'random-symmetry' : 'no-symmetry' );
 
 g_stateline_init(fen);
 startfen(zadanie);

 // the following automatic interventions are meant to correct the Basic Rules sets
 // if the solution contains a null move then passive mode is on automatically
 if (oklines.join('|').indexOf('--')>-1)
 {
	 el('passivebox').checked = true;
	 el('TimePerMove').value = '50';
 }
 if (opponent_tokens_only(fen)) el('TimePerMove').value = '50';
}

function opponent_tokens_only(FEN)
{
	var pieces = FEN.split(' ')[0];
	function niema(a) { return pieces.indexOf(a) == -1; }
	var meWhite = FEN.indexOf(' w ') > -1;
	if (meWhite) return niema('k') && niema('q') && niema('r') && niema('b') && niema('n') && niema('p');
	return niema('K') && niema('Q') && niema('R') && niema('B') && niema('N') && niema('P');
}

function ApplyAltRules(problem)
{
 var rules = problem[4];
 var ids = problem[6];
 problem[6] = applyrules(rules,ids);
}
         
function czykoniec(problem)
{
 var oklines = problem[1];
 var ids = problem[6];
 var a = new Array();
 for (var i=0; i<oklines.length; i++) a.push(i);
 return subset(a,ids);
}

function closeInsightButton()
{
	var bu = newel('button');
	bu.innerHTML = CloseText() + '&#10060;';
	bu.setAttribute('onclick','insight.style.display="none";el("showinsight").checked=false;');
	bu.style.margin = '0';
	//bu.style.float = 'right';
	return bu;
}

function showinsight(problem,correct)
{
 if (correct == null) correct = false;
 var insight = document.getElementById('insight');
 if (insight.style.display == 'none') return;
 //insight.style.resize = 'both';
 //insight.style.width = '23em';
 //insight.style.height = '360px';
 insight.style.borderWidth = '2px';
 insight.style.borderStyle = 'solid';
 insight.style.borderColor = pozadaniu ? '#56C259' : 'lightgray';
 insight.style.backgroundColor = pozadaniu ? '#faffff' : 'white'; 
 insight.style.paddingRight = '1.5em';
 insight.setAttribute('class','noselect');
 insight.style.overflowY = 'scroll';
 insight.style.lineHeight = '1.5';
 
 insight.innerHTML = '';
 insight.appendChild(closeInsightButton());
 var ol = document.createElement('ol'); ol.className = 'linelist';
 var oklines = problem[1];
 if (oklines.length == 0) return;
 var ids = problem[6];
 var ogony = [];
 for (var i=0; i<oklines.length; i++)
 {
  var line = sameruchyarray(oklines[i]);
  var item = document.createElement('li');
  appendruchygallery(item,line);
  var style = '';
  if (subset([i],ids) && !pozadaniu)
    style = 'color:gray; font-style:italic;';
  item.setAttribute('style',style);
  item.style.marginBottom = '0.5em';
  ol.appendChild(item);
  {
   var moves = sameruchyarray(document.getElementById('PgnTextBox').value);
   var spans = item.getElementsByTagName('span');
   var koniec = false; var n = 0;
   do{ if (line[n] == moves[n]) n++; else koniec = true; }
   while( !koniec && n < spans.length && n < moves.length )
   if (subset([i],ids)) n = (moves.length == line.length) ? n : 0;  
   ogony.push(n);
  }
 }
 var longest = 0;
 for (i=0; i<ogony.length; i++) if (ogony[i] > ogony[longest]) longest = i;
 var ile = ogony[longest];
 if (ile > 0)
 {
  var item = ol.getElementsByTagName('li')[longest];
  var spans = item.getElementsByTagName('span');
  for (var i=0; i<ile; i++) spans[i].style.fontWeight = 'bold';
 }
 insight.appendChild(ol); 
}

function startfen(problem)
{
	g_stateline_resign();
	if (zadanie) showinsight(problem);
	return;
 changeFEN(problem[0]);
 showinsight(problem);
 clearalert();
 clearindicatemove();
 clearcheck();
 cancelselected();
 return;
}

function success(problem)
{
 pozadaniu = true;
 showinsight(problem);
 //g_puzzle_setselfplay(!el('engineon').checked);
 g_puzzle_setselfplay(true);
 if (el('selfplayexamtip'))
 {
	 el('selfplayexamtip').remove();
 }
 g_stateline_refresh();
}

function playmove(ruch)
{
 if (ruch == '--') { playnullmove(); return; }

 var moves = GenerateValidMoves();
 var move = null;
 for (var i=0; i<moves.length; i++)
  if (GetMoveSAN(moves[i]).indexOf(ruch)==0) move = moves[i];
 if (move == null)
 {
  alert('ERROR: cannot play '+ruch);
  return;
 }
 g_stateline_makemove(move);
 /*UpdatePgnTextBox(move);
 if (InitializeBackgroundEngine()) g_backgroundEngine.postMessage(FormatMove(move));
 g_allMoves[g_allMoves.length] = move;
 MakeMove(move);
 UpdateFromMove(move);
 aftermove(move);*/
}

var g_ruch; function zagraj_g_ruch()
{
 playmove(g_ruch);
 showinsight(zadanie);
 userthinkingstyle();
}

function clearalert()
{
 document.getElementById('correct').style.display = 'none';
 document.getElementById('juzkoniec').style.display = 'none';
 document.getElementById('keeponsolving').style.display = 'none';
 document.getElementById('wrongmovealert').style.display = 'none';
 document.getElementById('limitalert').style.display = 'none';
 el('alert').style.display = 'none';
 el('alert').style.visibility = 'visible';
}
function revealalert()
{
	 el('alert').style.display = 'inline-block';
}

function correctalert() { revealalert(); el('correct').style.display = 'inline'; }
function correctalert()
{
	revealalert(); el('correct').style.display = 'inline';
	var ileoklines = zadanie[1].length;
	if (ileoklines > 0)
	{
		var ids = zadanie[6];
		var percent = Math.ceil(100* ids.length / ileoklines);
		if (percent < 100) el('percentage').innerHTML = percent + '%';
		else el('percentage').innerHTML = '';
	}
}


function juzkoniecalert()
{
	el('percentage').innerHTML = '';
 revealalert();
 el('juzkoniec').style.display = 'inline';
 if (puzzleset) puzzleset_marksuccess(puzzleset_currentnumber());
 else galogg('puzzle-success','-');
}
function keepalert() { revealalert(); el('keeponsolving').style.display = 'inline'; }
function wrongmovealert(ile)  { revealalert(); el('wrongmovealert').style.display = 'inline'; galogg('wrongmove',ile); }
function keepwrongmovealert() { revealalert(); el('wrongmovealert').style.display = 'inline'; }
function limitalert(ile) { revealalert(); el('limitalert').style.display = 'inline'; galogg('movelimit',ile); }

var g_ids_safe = [];
function undoids() { zadanie[6] = g_ids_safe; }
function undoids()
{
	function arraysEqual(a, b)
	{
		if (a === b) return true;
		if (a == null || b == null) return false;
		if (a.length != b.length) return false;
		for (var i = 0; i < a.length; ++i) { if (a[i] !== b[i]) return false; }
		return true;
	}
	var wycofanie = !arraysEqual(zadanie[6],g_ids_safe);
	zadanie[6] = g_ids_safe;
	if (wycofanie)
	{
		g_puzzle_setselfplay(false);
		g_stateline_refresh();
	}
}


function niemazadaniaporuchuczlowieka()
{
 if (document.getElementById('nocomp').checked) return;
 if (document.getElementById('passivebox').checked) reactpassively();
 else SearchAndRedraw();
}

function declaresuccess() { clearalert(); correctalert(); juzkoniecalert(); success(zadanie); }


function defaultReactionToCzlowiekzagral()
{
 if (document.getElementById('bothbox').checked == false)
 {
  if (document.getElementById('passivebox').checked == false)
   forcemove();
  else
   reactpassively();
 }
}

function verifysuccess(problem,seq)
{
	var bierki = GetFen().split(' ')[0];
	function no(a) { return bierki.indexOf(a) == -1; }
	function opponentempty()
	{
		var noWhite = no('K') && no('Q') && no('R') && no('B') && no('N') && no('P') && no('T');
		var noBlack = no('k') && no('q') && no('r') && no('b') && no('n') && no('p') && no('t');
		return (g_toMove) ? noWhite : noBlack;
	}
 var ilezagral = (1+tylkoruchy(seq).split('_').length)/2;
 var movelimit = problem[3];
 if (movelimit > 0 && ilezagral > movelimit)
 {
	 clearalert(); limitalert(ilezagral);
	 defaultReactionToCzlowiekzagral();
	 return;
 }
 var successindeed = false;
 if (successrule == 'win' && g_stateline_result() == 1.5) successindeed = true;
 if (successrule == 'win' && ismate()) successindeed = true;
 if (successrule == 'stalemate' && isstalemate()) successindeed = true;
 if (successrule == 'draw' && isdraw()) successindeed = true;
 if (successrule == 'destroy' && opponentempty()) successindeed = true;
 if (successrule == 'survive' && ilezagral >= movelimit) successindeed = true;
 if (successindeed) declaresuccess();
 else
 {
	 if (movelimit > 0 && ilezagral >= movelimit) { clearalert(); limitalert(ilezagral); }
	 defaultReactionToCzlowiekzagral();
 }
}

function czlowiekzagral(problem,seq)
{
 if (g_puzzle_czyselfplay()) return;

 if (successrule) { verifysuccess(problem,seq); return; }

 var seq = tylkoruchy(seq);
 var oklines = [].concat( problem[1] );
 if (pozadaniu || oklines.length == 0)
 { niemazadaniaporuchuczlowieka(); return; }

 for (var i=0; i<oklines.length; i++)
 {
  var x = tylkoruchy(oklines[i]).indexOf(seq);
  if (x == 0 && !subset([i],problem[6]) )
  {
   if (seq == tylkoruchy(oklines[i])) // end of line reached
   {
    g_ids_safe = [].concat(problem[6]);
    problem[6].push(i); // line solved, drops out of problem
    ApplyAltRules(problem);
    showinsight(problem,true);
    clearalert(); correctalert();
    if (czykoniec(problem)) { juzkoniecalert(); success(problem); }
                       else { keepalert(); /* startfen(problem); */ }
    return;
   }
   // not end of line yet
   
   var reply = tylkoruchy(oklines[i]).substr(seq.length).split('_')[1];
   var ms = document.getElementById('TimePerMove').value;
   if (document.getElementById('bothbox').checked == false)
   { 
    g_ruch = reply;
	enginethinkingstyle();
    setTimeout(zagraj_g_ruch, ms); //playmove(reply);
   }
   showinsight(problem);
   return;
  }
 }

 // what happens when current line includes an okline
 for (i=0; i<oklines.length; i++)
 {
  if (seq.indexOf(tylkoruchy(oklines[i]))==0)
  { 
    showinsight(problem,true);
    clearalert(); correctalert();
    if (czykoniec(problem)) { juzkoniecalert(); success(problem); }
                       else { keepalert(); }
    return;   
  }
 }

 // what happens when current line is properly included in a dropped okline
 for (var i=oklines.length-1; i>=0; i--)
 {
  var x = tylkoruchy(oklines[i]).indexOf(seq);
  if (x == 0)
  {
   // not end of line yet
   var reply = tylkoruchy(oklines[i]).substr(seq.length).split('_')[1];
   var ms = document.getElementById('TimePerMove').value;
   if (document.getElementById('bothbox').checked == false)
   { 
    g_ruch = reply;
    setTimeout(zagraj_g_ruch, ms);
   }
   showinsight(problem);
   return;
  }
 }
 
 // no correct lines with this sequence

 if (el('wrongmovealertbox').checked) g_stateline_firstwrong(); // append ? to the SAN of the current move but only if no previous move has ?
 
 var wronglines = problem[2];
 if (wronglines.length>0)
 for (var i=0; i<wronglines.length; i++)
 {
  var x = tylkoruchy(wronglines[i]).indexOf(seq);
  if (x == 0)
  {
   var reply = tylkoruchy(wronglines[i]).substr(seq.length).split('_')[1];
   playmove(reply);
   return;
  }
 }

 //wrong move, computer replies by engine
 var ilezagral = (1+seq.split('_').length)/2;
 
 if (document.getElementById('wrongmovealertbox').checked)
   { clearalert(); wrongmovealert(ilezagral); }

 var movelimit = problem[3];
 if (movelimit > 0)
 { 
  if (ilezagral >= movelimit)
  {
   clearalert(); limitalert(ilezagral); //startfen(problem);
  }
 }

 defaultReactionToCzlowiekzagral();
 // the following two lines are useful only in passive mode to keep the alerts from disappearing
 if (el('wrongmovealertbox').checked) el('wrongmovealert').style.display = 'inline';
 if (movelimit > 0 && ilezagral >= movelimit) { clearalert(); el('alert').style.display = 'inline-block'; el('limitalert').style.display = 'inline'; }
}


function documentready()
{
 g_timeout = 1000;
 UINewGame();
 zadanie = mateinit(fen,matein,false);
 startfen(zadanie);
}


// findmate.php textarea stuff

function resetfen()
{
 newbutton();
}

function makeMove_returnIndex(SANmove)
{
 var moves = GenerateValidMoves().sort(function(a,b){return a-b});
 var ile = moves.length;
 for (var j=0; j<ile; j++)
  if ( GetMoveSAN(moves[j]).indexOf(SANmove) == 0 )
  {
   MakeMove(moves[j]);
   return j;
  }
  
 if ('QRBN'.indexOf(SANmove[0])>-1)
 if ('abcdefgh12345678'.indexOf(SANmove[1])>-1)
 if ('abcdefgh'.indexOf(SANmove[2])>-1)
 if ('12345678'.indexOf(SANmove[3])>-1)
 {
  var newmove = SANmove[0] + SANmove.substr(2);
  return makeMove_returnIndex(newmove); 
 }
 
 if (SANmove == '--') { makenullmove(); return j; }
  
 return -1;
}

function wariant2indexes(line,nr)
{
 resetfen();
 var ile = line.length;
 if (ile == 0) return [];
 var ret = [];
 for (var i=0; i<ile; i++)
 {
  var SANmove = line[i];
  var index = makeMove_returnIndex(SANmove);
  if (index>-1)
  {
   ret.push(index);
  }
  else
  {
   document.getElementById('errorsdiv').style.display = 'inline';
   var errors = document.getElementById('errormessages');
   var errorline = [];
   for (var j=0; j<ile; j++)
    if (j == i) errorline.push( '<b>'+line[i]+'</b>' );
    else if (j > i) errorline.push('<i>'+line[j]+'</i>');
    else errorline.push( line[j] ); 
   var blackstarts = fen.indexOf(' b ') > -1;
   errors.innerHTML += '<p>'+nr+') '+ponumerujarray(errorline,blackstarts,'')+'</p>';
   return false;
  }
 }
 return ret;
}

/*
function wariant2indexes(line,nr)
{
 resetfen();
 if (line.length == 0) return [];
 var ret = [];
 for (var i=0; i<line.length; i++)
 {
  var SANmove = line[i];
  var index = makeMove_returnIndex(SANmove);
  if (index>-1)
  {
   ret.push(index);
  }
  else
  {
   document.getElementById('errorsdiv').style.display = 'inline';
   var errors = document.getElementById('errormessages');
   var errorline = [];
   for (var j=0; j<line.length; j++)
    if (j == i) errorline.push( '<b>'+line[i]+'</b>' );
    else if (j > i) errorline.push('<i>'+line[j]+'</i>');
    else errorline.push( line[j] ); 
   var blackstarts = fen.indexOf(' b ') > -1;
   errors.innerHTML += '<p>'+nr+') '+ponumerujarray(errorline,blackstarts,'')+'</p>';
   return false;
  }
 }
 return ret;
}
*/

function texttoindexes()
{
 prettify(); prettify();
 document.getElementById('errormessages').innerHTML = '';
 document.getElementById('errorsdiv').style.display = 'none';
 var a = gettext();
 if (a.length == 0) return [];
 a = a.split('\n');
 var indianty = [];
 for (var i=0; i<a.length; i++)
 {
  if (a[i])
  {
   var sameruchy = tylkoruchy(a[i]);
   if (sameruchy)
   { 
    var line = sameruchy.split('_');
    var wariant = wariant2indexes(line,i+1);
    if (wariant) indianty.push(wariant);
   }
  }
 }
 resetfen();
 return indianty;
}

function removecurrentline()
{
 var movelist = document.getElementById('movelist').innerHTML;
 if (movelist == '' || movelist == '&nbsp;')
 {
  alert('Make some moves on the board.');
  return;
 }
 if (confirm('This will remove all lines beginning with:\n\n'+movelist))
 {
  var a = gettext();  
  if (a == '') return;
  a = a.split('\n');
  var len = a.length;
  var b = '';
  var ile = 0;
  for (var i=0; i<len; i++)
  {
   if (a[i].indexOf(movelist) == -1)
    b += a[i]+'\n';
   else
    ile++;
  }
  if (ile>0)
  {
   puttext(b);
   updatepuzzle();
   alert(ile + ' lines were removed.')
  }
  else
  {
   alert('But there are no lines beginning with:\n\n'+movelist);
  }
 }
}

function prettify()
{
 var blackstarts = fen.indexOf(' b ')>-1;
 var a = gettext();
 var b = '';
 if (a.length == 0) return [];
 a = a.split('\n');
 for (var i=0; i<a.length; i++)
 {
  if (a[i] != '')
  {
   var sameruchy = tylkoruchy(a[i]);
   if (sameruchy)
   { 
    b += (i+1) + ') ';
    b += ponumerujarray(sameruchy.split('_'),blackstarts,'') + '\n';
   }
  }
 }
 puttext(b);
}

function addcurrentline()
{
 var movelist = document.getElementById('PgnTextBox').value;
 if (movelist == '' || movelist == ' ')
 {
  alert('Make some moves on the board.');
  return;
 }
 movelist = tylkoruchy(movelist);
 movelist = movelist.split('_').join(' ');
 blackstarts = fen.indexOf(' b ')>-1;
 movelist = ponumeruj(movelist+' ',blackstarts);
 puttext(gettext()+movelist+'\n');
 prettify();
 movelist = document.getElementById('movelist');
 //movelist.innerHTML = '<b>'+movelist.innerHTML+'</b>';
 movelist.style.fontWeight = 'bold';
 document.getElementById('plusbutton').style.display = 'none';
 updatenicelines();
}

// pgn to puzzle

function trim_first_halfmove()
{
 var blackstarts = fen.indexOf(' b ') >- 1;
 var a = gettext().split('\n');
 var b = '';
 var firstmoves = [];
 for (var i=0; i<a.length; i++)
 {
  var line = sameruchyarray( a[i] );
  if (line.length > 0)
  {
   firstmoves.push ( line.shift() );
   line = line.join(' ') + ' ';
   b += ponumeruj(line,!blackstarts) + '\n';
  }
 }
 var ile = firstmoves.length;
 if (ile == 0) return; 
 if (ile > 1)
 {
  for (var i=1; i<ile; i++)
   if (firstmoves[i] != firstmoves[0])
   {
    alert('two first halfmoves: '+firstmoves[0]+', '+firstmoves[i]);
    return;
   }
 }
 
 if (!confirm("The first half-move "+firstmoves[0]+" will be removed from all lines.")) return;
 
 puttext(b);
 
 resetfen();
 playmove(firstmoves[0]);
 fen = FENarray[1];
 document.getElementById('FenTextBox').value = fen;
 absorbFEN();
 //flipcyfra = (flipcyfra == '0') ? '1' : '0';
 transferfen = flipcyfra + replace(fen," ","_");
 transferfen = replace(transferfen,"/","X");
 matein = 0;
 //updatepuzzle();
 newbutton();
 prettify();
}

function pozafromFENtokens(fen)
{
 if (!fen) return "";
 var width = 8, height = 8;
 var fenek = fen.split(' ');
 var horizontals = fenek[0].split('/');
 if (horizontals.length != height) return "";
 var pozycja = "";
 for (var y=height-1; y>=0; y--)
 {
  var hora = horizontals[y];
  while (hora != "")
  {
   switch(hora.charAt(0))
   {
    case 'K' : pozycja += "K"; break;
    case 'k' : pozycja += "k"; break;
    case 'Q' : pozycja += 'Q'; break;
    case 'q' : pozycja += 'q'; break;
    case 'R' : pozycja += 'R'; break;
    case 'r' : pozycja += 'r'; break;
    case 'B' : pozycja += 'B'; break;
    case 'b' : pozycja += 'b'; break;
    case 'N' : pozycja += 'N'; break;
    case 'n' : pozycja += 'n'; break;
    case 'P' : pozycja += 'P'; break;
    case 'p' : pozycja += 'p'; break;
    case 'T' : pozycja += 'T'; break;
    case 't' : pozycja += 't'; break;
    case '1' : pozycja += '_'; break;
    case '2' : pozycja += "__"; break;
    case '3' : pozycja += '___'; break;
    case '4' : pozycja += '____'; break;
    case '5' : pozycja += '_____'; break;
    case '6' : pozycja += '______'; break;
    case '7' : pozycja += '_______'; break;
    case '8' : pozycja += '________'; break;
    default  : return ""; 
   }
   hora = hora.substring(1,hora.length);
  }
 }
 if (pozycja.length != width*height) return "";
 return '_' + pozycja;
}


function initfromabsorbfen()
{
 if (pozafromFENtokens(document.getElementById('FenTextBox').value)=='')
 {
  alert('wrong FEN');
  return;
 }
 absorbFEN();
 updateczyjruch();
 flipcyfra = (fen.indexOf(' b ')>-1) ? '1' : '0';
 transferfen = flipcyfra + replace(fen," ","_");
 transferfen = replace(transferfen,"/","X");
 matein = 0;
 puttext('');
 updatepuzzle();
 g_stateline_resetFEN(el('FenTextBox').value);
}

function onsubmitpgnform()
{
	importPGNclicked(); return;
 var form = document.forms.pgnform;
 var pgntext = document.getElementById('pgnimportarea').value;
 if (pgntext == '') return;
 form.pgn.value = encodeURIComponent(pgntext);
 var apronus = window.location.href.indexOf('//www.apronus.com') > 0;
 if (apronus) form.action = '//www.apronus.com/chess/puzzle/editor.php';
         else form.action = '#';
}

function importPGN()
{
 document.getElementById('readPGNwindow').style.display = 'block';
 document.getElementById('pgnimportarea').focus();
}



function validpuzzle()
{
 var saveruchy = sameruchyarray(document.getElementById('PgnTextBox').value);
 var ret = '?p='+transferfen;
 ret += '&N=' + jakilimit();
 ret += '&w=' + zakodujwarianty(texttoindexes());
 resetgarbochess(saveruchy);
 var header = document.getElementById('headerhtml').innerHTML;
 if (header != mateinheader()) ret += '&h='+encodeURIComponent(header).replace(/'/g,'%27');
 if (document.getElementById('errorsdiv').style.display == 'inline') return false;

 var wrong = document.getElementById('wrongmovealertbox').checked ? 1 : 0;
 var all = document.getElementById('altbox').checked ? 2 : 0;
 var blindfold = document.getElementById('blindfold').checked ? 4 : 0;
 var solution = document.getElementById('solutionbox').checked ? 8 : 0;
 var both = document.getElementById('bothbox').checked ? 16 : 0;
 var passive = document.getElementById('passivebox').checked ? 32 : 0;
 var selfplay = document.getElementById('selfplaybox').checked ? 64 : 0;
 var noresult = document.getElementById('noresultbox').checked ? 128 : 0;
 var showme = el('showmebox').checked ? 0 : 256;
 var enginehelp = el('enginehelpbox').checked ? 0 : 512;
 var exam = el('exambox').checked ? 1024 : 0;
 var mode = wrong + all + blindfold + solution + both + passive + selfplay + noresult + showme + enginehelp + exam;
 if (mode != 1) ret += '&m='+mode;
 if (niema) ret += '&niema='+niema;
 var su = successruleselector.value;
 if (su != 'solution' && el('radiopuzzle').checked) ret += '&su='+su;
 if (el('langselect').selectedIndex > 0) ret += '&lang=' + el('langselect').value;
 
 var ms = parseInt(el('TimePerMove').value); if (isNaN(ms) || ms < 10) ms = 1000;
 if (ms != 1000) ret += '&ms='+ms;

 var pgn = el('pgnimportarea').value;
 if (pgn.indexOf('[White "Chesthetica') > -1)
 {
	 var number = pgn.match(/\[Event "CGCP (.+)"\]/); number = (number) ? number[1] : '';
	 if (number.length == 5 ) ret += '&chesthetica=' + number;
 }

 if (passive)
 {
  const V = el('gurupassiveV').value;
  if (!isNaN(parseInt(V))) ret += '&V=' + V;
 }

 undecorate(); // to prevent arrows from showing in other tabs
 return ret;
}
function validURL()
{
	var prefix = 'https://www.apronus.com/chess/puzzle/';
	var puzzle = validpuzzle();
	return (puzzle) ? (prefix + puzzle) : false;
}



// iframe


function updateiframe(locationhref)
{
  var headerheight = document.getElementById('headerhtml').clientHeight;
  var iframeheight = headerheight + 621; 
  var iframestyle = "width:100%; height:"+iframeheight+"px;";
  var iframecode = "<iframe style='"+iframestyle+"'\n";
  iframecode += " src='"+locationhref+"'>\n</iframe>\n";
  document.getElementById('iframetext').value = iframecode;
  var iframe = document.getElementById('previewiframe');  
  iframe.src = locationhref;
  iframe.setAttribute("style", iframestyle);
}

function previewpuzzle()
{
 var src = validURL();
 if (src == false) return;
 var headerheight = document.getElementById('headerhtml').clientHeight;
 var iframeheight = headerheight + 621; 
 var iframestyle = "width:100%; height:"+iframeheight+"px;";
 var iframe = document.getElementById('previewiframe2');  
 iframe.src = src;
 iframe.setAttribute("style", iframestyle);
 document.getElementById('previewwindow').style.display = 'block';   
}

function iframeCode(src)
{
 var pgn = fullPGNtext();
 var headerheight = document.getElementById('headerhtml').clientHeight;
 var iframeheight = headerheight + 621; 
 var iframestyle = "width:100%; height:"+iframeheight+"px;";
 var iframecode = "<iframe style='"+iframestyle+"'\n";
 iframecode += " src='"+src+"'>\n\n"+pgn+"</iframe>\n";
 return iframecode; 
}

function iframeexport()
{
 var src = validURL();
 if (src == false) return;
 var code = iframeCode(src);
 document.getElementById('iframeexportarea').rows = ''+code.split('\n').length; 
 document.getElementById('iframeexportarea').value = code;
 document.getElementById('iframeexportwindow').style.display = 'block';
 document.getElementById('iframeexportarea').select();
}

function linkexport()
{
 var src = validURL();
 if (src == false) return;
 var code = '<a target="_blank" href="'+src+'"\n>_____puzzle______</a>';
 code += '\n<!--\n\n' + fullPGNtext() + '-->\n';
 document.getElementById('linkexportarea').rows = ''+(3+code.split('\n').length); 
 document.getElementById('linkexportarea').value = code;
 document.getElementById('linkexportwindow').style.display = 'block';
 document.getElementById('linkexportarea').select();
}

function urlexport()
{
 var src = validURL();
 if (src == false) return;
 var code = src;
 var tiny = 'http://tinyurl.com/create.php?url='+encodeURIComponent(src);
 document.getElementById('tinyurl').href = tiny;
 document.getElementById('urlexportarea').rows = '8'; 
 document.getElementById('urlexportarea').value = code;
 document.getElementById('urlexportwindow').style.display = 'block';
 document.getElementById('urlexportarea').select();
}

function smartlines(arr)
{
	// we get an array of [A,B,C] arrays, assuming that A is constant (the first winning move is unique)
	var ile = arr.length;
	if (ile == 0) return [];
	
	for (var i=0; i < ile; i++) if (arr[i][0] != arr[0][0]) return arr; // assumption is wrong, do nothing
	
	arr.reverse(); // captures first
	
	function T(a)
	{
		var ret = [];
		for (var i=0; i<ile; i++) if (arr[i][1] == a) ret.push( arr[i][2] );
		return ret;
	}
	function disjoint(A,B)
	{
		if (A.length * B.length == 0) return true;
		for (var i=A.length-1; i>=0; i--)
			for (var j=B.length-1; j>=0; j--)
				if (A[i] == B[j]) return false;
		return true;
	}
	/*function sort(arr)
	{
		var ile = arr.length;
		if (ile == 0) return [];
		var captures = [], rest = [];
		for (var i=0; i < ile; i++)
		{
			var move = arr[i][1];
			
		}
	}*/
	
	var A = []; // singleton fibers
	var Aarr = [];
	for (var i=0; i<ile; i++)
	{
		var a = arr[i][1];
		var picka = true;
		for (var j=0; j<ile; j++)
		{
			var b = arr[j][1];
			if (a != b && !disjoint(T(a),T(b))) { picka = false; j = ile; }
		}
		if (picka) { A.push(a); Aarr.push(arr[i]); }
	}
	
	var nonAarr = [];
	for (var i=0; i<ile; i++) if (disjoint([arr[i][1]],A)) nonAarr.push( arr[i] );
	var Barr = [];
	nonAarr.reverse(); // captures last but next line reverses the order
	if (nonAarr.length) for (var i = nonAarr.length-1; i>=0; i--) if (T(nonAarr[i][1]).length == 1) Barr.push(nonAarr[i]);
	
	var juzbylo = [];
	var Carr = [];
	ile = Barr.length;
	if (ile) for (var i=0; i < ile; i++)
	{
		var b = Barr[i][2];
		if (disjoint([b],juzbylo)) Carr.push( Barr[i] );
		juzbylo.push(b);
	}
	
	var ret = Carr.concat(Aarr); // var ret = Aarr.concat(Carr);
	if (ret.length == 0) return arr;
	return ret;
}

function mateinheader()
{
 var ret = '<h1>';
 var N = document.getElementById('movelimit').value;
 var blackstarts = fen.indexOf(' b ') > -1;
 var who = blackstarts ? 'Black' : 'White';
 ret += who + ' to move and win by checkmate in ' + N;
 ret += (N==1) ? ' move' : ' moves';
 return ret + '</h1>';
}

function smartmate2()
{
	return indexlines(smartlines(matelines(2)));
}

function findmate(N)
{
 if (N >= 3)
 {
  var warning = 'Your computer will now look for all forced mates in ' + N + '.';
  warning += ' This may take a lot of time. ';
  warning += 'This may freeze your computer or crash your browser. ';
  warning += 'Save your work before you go on. ';
  warning += 'Click OK to proceed at your own risk. Click Cancel to stop now.';
  if (!confirm(warning)) return;
 }

 var a = matelines(N);
 if (a.length == 0) { alert('But there is no forced mate in '+N+'.'); return; }
 alert('Found forced mate in '+N);
 
 if (N == 2 && el('smartlinesbox').checked) a = smartlines(a); else a.reverse();
 
 initfromabsorbfen();
 
 var blackstarts = fen.indexOf(' b ') > -1;
 var oklines = SANindexlines(indexlines(a),blackstarts); 
 puttext(oklines.join('\n'));

 document.getElementById('movelimit').value = N;
 if (document.getElementById('headtext').value == '<h1>(header missing)</h1>')
 {
  document.getElementById('headtext').value = mateinheader();
  document.getElementById('headerhtml').innerHTML = mateinheader();
 }
 prettify();
 updatenicelines();
 el('autoheaderselect').value = 'mate';
 
 /*var matrix = [];
 for (var i=0; i<oklines.length; i++) matrix.push(oklines[i].split(' '));
 console.log(matrix);
 if (N == 2) smartlines(matrix);*/
}

// puzzle to pgn

function addlinetopgn(line,pgn)
{
 if (line.length==0) return pgn;
 if (pgn.length==0) return line;
 var ret = [];
 var a,b; 
 while (a = line.shift())
 {
  if (pgn.length == 0)
  {
   ret.push( '()' );
   ret.push(a);
   while (a = line.shift()) ret.push(a);
   return ret;
  }
  b = pgn.shift();
  if (a == b) ret.push(a);
  else
  {
   if ( b.charAt(0) != '(' )
   {
    ret.push(a);
    ret.push( '('+ [b].concat(pgn) + ')' );
    while(a = line.shift()) ret.push(a);
    return ret;
   }
   else
   {
    ret.push(b);
    line = [a].concat(line);
   }
  }
 }
 if (pgn.length > 0) ret.push( '('+pgn+')' );
 return ret;
}

function pgnstring2array(pgn)
{
 if (pgn == '') return [];
 var x = pgn.indexOf('(');
 if (x == -1) return pgn.split(',');
 var ret = pgn.substr(0,x-1).split(',');
 var nawias = '(';
 var depth = 1;
 while (depth > 0)
 {
  x++;
  nawias += pgn.charAt(x);
  if (pgn.charAt(x)=='(') depth++;
  if (pgn.charAt(x)==')') depth--;
 }
 ret.push(nawias);
 return ret.concat( pgnstring2array(pgn.substr(x+2)) ); 
}

function ponumerujpgn(pgn,fullmovenumber,blacktomove)
{
 if (pgn.length==0) return '';
 var ret = '';
 var a;
 if (blacktomove)
 {
  a = pgn.shift();
  ret = fullmovenumber + '...' + a;
  if (pgn.length > 0) ret += ' ';
  fullmovenumber++;
  blacktomove = false;
 }
 while(pgn.length > 0)
 {
  a = pgn.shift();
  if (a.charAt(0)!='(')
  {
   ret += blacktomove ? '' : (fullmovenumber + '.');
   ret += a;
   if (pgn.length > 0) ret += ' ';
   if (blacktomove) fullmovenumber++;
   blacktomove = !blacktomove;
  }
  else
  {
   var inpgn = pgnstring2array(a.substr(1,a.length-2));
   var infullmovenumber = blacktomove ? fullmovenumber : (fullmovenumber-1);
   inpgn = ponumerujpgn(inpgn,infullmovenumber,!blacktomove);
   ret += '(' + inpgn + ')';
   if (pgn.length > 0) ret += ' ';
   return ret + ponumerujpgn(pgn,fullmovenumber,blacktomove);
  }
 }
 return ret;
}

function wordwrap( str, width, brk, cut ) {
 
    brk = brk || '\n';
    width = width || 75;
    cut = cut || false;
 
    if (!str) { return str; }
 
    var regex = '.{1,' +width+ '}(\\s|$)' + (cut ? '|.{' +width+ '}|.+$' : '|\\S+?(\\s|$)');
 
    return str.match( RegExp(regex, 'g') ).join( brk );
 
}

function fullPGNtext(textlines)
{
 if (textlines == null) textlines = gettext().split('\n');
 var lines = [], line;
 while( textlines.length > 0 )
 {
  line = sameruchyarray(textlines.shift());
  if (line.length > 0) lines.push(line);
 }
 var pgn = [];
 while( lines.length > 0 )
 {
  line = lines.shift();
  pgn = addlinetopgn(line,pgn);
 }
 var moves = ponumerujpgn(pgn,fullmovenumber(fen),fen.indexOf(' b ')>-1);

 moves = wordwrap(moves,80,'\n',false);
 var currentTime = new Date();
 var month = currentTime.getMonth() + 1;
 if (0+month<10) month = '0'+month; 
 var day = currentTime.getDate();
 if (0+day<10) day = '0'+day;
 var year = currentTime.getFullYear();
 var pgn = '';
 pgn += '[Event "Chessboard Editor at Apronus.com"]\n';
 pgn += '[Site "https://www.apronus.com/chess/puzzle/editor.php"]\n';
 pgn += '[Date "'+year+'.'+month+'.'+day+'"]\n';
 pgn += '[Round "-"]\n';
 pgn += '[White "?"]\n';
 pgn += '[Black "?"]\n';
 pgn += '[Result "*"]\n';
 if (fen != 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')
 {
  pgn += '[SetUp "1"]\n';
  pgn += '[FEN "' + fen + '"]\n';
 }
 if (moves != '') pgn += '\n' + moves +' *\n\n'; 
 pgn += '\n';
 return pgn;
}


function puzzle2pgn()
{
 if (validURL()==false) return;
 var pgn = fullPGNtext();
 var output = document.getElementById('pgnexportarea');
 output.rows = ''+pgn.split('\n').length;
 output.value = pgn;
 document.getElementById('pgnexportwindow').style.display = 'block';
 output.select(); 
 return;
}

function openpuzzleeditor()
{
 var oklines = [].concat(zadanie[1]);
 var pgn = fullPGNtext(oklines);
 var form = document.forms.editpuzzleform;  
 form.pgn.value = pgn;
 var h = document.getElementById('h1header').innerHTML;
 form.h.value = encodeURIComponent(h);
 var wrong = document.getElementById('wrongmovealertbox').checked ? 1 : 0;
 var all = document.getElementById('altbox').checked ? 2 : 0;
 var blindfold = document.getElementById('blindfold').checked ? 4 : 0;
 var solution = document.getElementById('showinsight').checked ? 8 : 0;
 var both = document.getElementById('bothbox').checked ? 16 : 0;
 var passive = document.getElementById('passivebox').checked ? 32 : 0;
 var mode = wrong + all + blindfold + solution + both + passive;
 form.m.value = mode;
 return true;
}

function newpuzzleeditor()
{
 var pgn = fullPGNtext();
 var form = document.forms.newpuzzleeditorform;  
 form.pgn.value = pgn;
 var h = document.getElementById('headtext').value;
 form.h.value = encodeURIComponent(h);
 var wrong = document.getElementById('wrongmovealertbox').checked ? 1 : 0;
 var all = document.getElementById('altbox').checked ? 2 : 0;
 var blindfold = document.getElementById('blindfold').checked ? 4 : 0;
 var solution = document.getElementById('solutionbox').checked ? 8 : 0;
 var both = document.getElementById('bothbox').checked ? 16 : 0;
  var passive = document.getElementById('passivebox').checked ? 32 : 0;
 var mode = wrong + all + blindfold + solution + both + passive;
 form.m.value = mode;
 form.flip.value = document.getElementById('flipbox').checked ? '1' : '0';
 form.editpuzzle.value = document.getElementById('puzzleeditorbox').checked ? '1' : '0';
 form.playcomputer.value = document.getElementById('engineon').checked ? '1' : '0';
 var current = sameruchyarray(document.getElementById('PgnTextBox').value);
 form.c.value = zakodujwarianty( [wariant2indexes(current,0)] );
 resetgarbochess(current);
 return true;
}

function clonethisURL()
{
 var saveruchy = sameruchyarray(document.getElementById('PgnTextBox').value);
 var c = zakodujwarianty( [wariant2indexes(saveruchy,0)] );
 resetgarbochess(saveruchy);
 var w = zakodujwarianty(texttoindexes());
 resetgarbochess(saveruchy);
 if (document.getElementById('errorsdiv').style.display == 'inline')
 {
  alert('Error in list of lines'); return;
 }

 var apronus = window.location.href.indexOf('//www.apronus.com') > 0;
 var wbeditor = window.location.href.indexOf('wbeditor') > -1;
 var url = '';
 if (apronus || wbeditor) url = 'https://www.apronus.com/chess/puzzle/editor.php';
 
 //url += '?editpuzzle=' + (document.getElementById('puzzleeditorbox').checked ? '1' : '0');
 //url += '&playcomputer=' + (document.getElementById('engineon').checked ? '1' : '0');
 var flipcyfra = document.getElementById('flipbox').checked ? '1' : '0';
 url += '?p=' +  flipcyfra + transferfen.substr(1);  
 url += '&c='+ c;
 url += '&w=' + w; 
 var h = document.getElementById('headerhtml').innerHTML;
 url += '&h=' + encodeURIComponent(h); 
 url += '&N=' + jakilimit();  
 var wrong = document.getElementById('wrongmovealertbox').checked ? 1 : 0;
 var all = document.getElementById('altbox').checked ? 2 : 0;
 var blindfold = document.getElementById('blindfold').checked ? 4 : 0;
 var solution = document.getElementById('solutionbox').checked ? 8 : 0;
 var both = document.getElementById('bothbox').checked ? 16 : 0;
 var passive = document.getElementById('passivebox').checked ? 32 : 0;
 var selfplay = document.getElementById('selfplaybox').checked ? 64 : 0;
 var mode = wrong + all + blindfold + solution + both + passive + selfplay;
 url += '&m=' + mode;
 if (niema) url += '&niema='+niema;
 var su = successruleselector.value;
 if (su != 'solution') url += '&su='+su;
 var ms = parseInt(el('TimePerMove').value); if (isNaN(ms) || ms < 10) ms = 1000;
 if (ms != 1000) url += '&ms='+ms;
 return url;
}

function puzzlenewwindow()
{
 window.open(clonethisURL());
}

function thischessboardURL(editorpath)
{
 var saveruchy = sameruchyarray(document.getElementById('PgnTextBox').value);
 var c = zakodujwarianty( [wariant2indexes(saveruchy,0)] );
 resetgarbochess(saveruchy);
 var flipcyfra = document.getElementById('flipbox').checked ? '1' : '0';
 var url = ''+editorpath;
 url += '?fen=' +  flipcyfra + transferfen.substr(1);  
 url += '&c='+ c;
 url += '&editpuzzle=0';
 return url;
}

function passivelogURL()
{
 var saveruchy = sameruchyarray(document.getElementById('PgnTextBox').value);
 var c = zakodujwarianty( [wariant2indexes(saveruchy,0)] );
 resetgarbochess(saveruchy);
 var apronus = window.location.href.indexOf('//www.apronus.com') > 0;
 var wbeditor = window.location.href.indexOf('wbeditor') > -1;
 var url = 'editor.php';
 if (apronus || wbeditor) url = '//www.apronus.com/chess/puzzle/editor.php';
 var flipcyfra = document.getElementById('flipbox').checked ? '1' : '0';
 url += '?fen=' +  flipcyfra + transferfen.substr(1);  
 url += '&c='+ c;
 url += '&w='+ c;
 return url;
}


function playthisagainstcomputer()
{
 window.open(playthisURL());
}

function playthisURL()
{
 let  url = 'https://www.apronus.com/chess/playcomputer/';
 const flipcyfra = el('flipbox').checked ? '1' : '0';
 let fennow = el('FenTextBox').value;
 fennow = replace(fennow,' ','_');
 fennow = replace(fennow,'/','X');
 fennow = flipcyfra + fennow;
 url += '?fen='+fennow;  
 return url;
}

function nicelines(lines)
{
 var insight = document.createElement('fieldset');
 insight.style.paddingRight = '1.5em';
 insight.style.lineHeight = '1.5';
 insight.innerHTML = '';
 if (lines.length == 0) return insight;
 var ol = document.createElement('ol');
 var ogony = [];
 for (var i=0; i<lines.length; i++)
 {
  var line = lines[i];
  var item = document.createElement('li');
  appendruchygallery(item,line);
  item.style.marginBottom = '0.5em';
  ol.appendChild(item);
  {
   var moves = sameruchyarray(document.getElementById('PgnTextBox').value);
   var spans = item.getElementsByTagName('span');
   var koniec = false; var n = 0;
   do{ if (line[n] == moves[n]) n++; else koniec = true; }
   while( !koniec && n < spans.length && n < moves.length )  
   ogony.push(n);
  }
 }
 var longest = 0;
 for (i=0; i<ogony.length; i++) if (ogony[i] > ogony[longest]) longest = i;
 var ile = ogony[longest];
 if (ile > 0)
 {
  var item = ol.getElementsByTagName('li')[longest];
  var spans = item.getElementsByTagName('span');
  for (var i=0; i<ile; i++) spans[i].style.fontWeight = 'bold';
 }
 insight.appendChild(ol);
 return insight; 
}

function updatenicelines()
{
 if (document.getElementById('edytorekcontainer').style.display != 'none') return; 
 var container = document.getElementById('nicelinescontainer');
 container.innerHTML = '';
 container.style.display = 'block';
 var oklines = gettext().split('\n');
 var lines = [];
 while (oklines.length > 0)
 {
  var a = sameruchyarray(oklines.shift());
  if (a.length > 0) lines.push(a); 
 }
 document.getElementById('edytorekcontainer').style.display = 'none';
 container.appendChild(nicelines(lines));
}

function editedytorek()
{
 var edytorek = document.getElementById('edytorekcontainer');
 if (edytorek.style.display == 'none')
 { 
  edytorek.style.display = 'block';
  document.getElementById('nicelinescontainer').style.display = 'none';
  document.getElementById('edytorek').focus();
 }
 else
 {
  edytorek.style.display = 'none';
  updatenicelines();
 }
}

function edytorekonchange()
{
 if (validURL())
 {
  document.getElementById('edytorekcontainer').style.display = 'none';
  updatenicelines();
 }
 else
 {
  document.getElementById('edytorek').focus();
 }
}


// diagrams

function diagrameditorsubmit()
{
 var form = document.forms.diagrameditorform;
 var flip = document.getElementById('flipbox').checked;
 form.f.value = flip ? '1' : '';
 var fen = g_stateline[0].FEN;//GetFen();
 form.d.value = pozafromFENtokens(fen);
 form.z.value = (fen.indexOf('w')>-1) ? 'w' : 'b';
 return true;
}
function pozafromFEN(fen)
{
 var width = 8; var height = 8;
 if (!fen) return "";
 var fenek = fen.split(' ');
 var horizontals = fenek[0].split('/');
 if (horizontals.length != height) return "";
 var pozycja = "";
 for (var y=height-1; y>=0; y--)
 {
  var hora = horizontals[y];
  while (hora != "")
  {
   switch(hora.charAt(0))
   {
    case 'K' : pozycja += "K"; break;
    case 'k' : pozycja += "k"; break;
    case 'Q' : pozycja += 'Q'; break;
    case 'q' : pozycja += 'q'; break;
    case 'R' : pozycja += 'R'; break;
    case 'r' : pozycja += 'r'; break;
    case 'B' : pozycja += 'B'; break;
    case 'b' : pozycja += 'b'; break;
    case 'N' : pozycja += 'N'; break;
    case 'n' : pozycja += 'n'; break;
    case 'P' : pozycja += 'P'; break;
    case 'p' : pozycja += 'p'; break;
    case '1' : pozycja += '_'; break;
    case '2' : pozycja += "__"; break;
    case '3' : pozycja += '___'; break;
    case '4' : pozycja += '____'; break;
    case '5' : pozycja += '_____'; break;
    case '6' : pozycja += '______'; break;
    case '7' : pozycja += '_______'; break;
    case '8' : pozycja += '________'; break;
    default  : return ""; 
   }
   hora = hora.substring(1,hora.length);
  }
 }
 if (pozycja.length != width*height) return "";
 return '_' + pozycja;
}

function wariant2fens(line)
{
 if (line.length == 0) return [];
 resetfen();
 var ret = [GetFen()];
 for (var i=0; i<line.length; i++)
 {
  var index = makeMove_returnIndex(line[i]);
  if (index>-1) ret.push(GetFen()); else return [];
 }
 return ret;
}

function text2fens()
{
 prettify(); prettify();
 var a = gettext();
 if (a.length == 0) return [];
 var saveruchy = sameruchyarray(document.getElementById('PgnTextBox').value);
 a = a.split('\n');
 var indianty = [];
 for (var i=0; i<a.length; i++)
 {
  if (a[i])
  {
   var sameruchy = tylkoruchy(a[i]);
   if (sameruchy)
   { 
    var line = sameruchy.split('_');
    var wariant = wariant2fens(line);
    if (wariant) indianty = indianty.concat(wariant); else return [];
   }
  }
 }
 resetgarbochess(saveruchy);
 return indianty;
}

function onsubmit_animagram()
{
 var form = document.getElementById('animagramform');
 if (document.getElementById('oklinesbox').checked)
 {
  var fenarray = text2fens();
  if (fenarray.length > 0) form.a.value = fenarray2moviediagram(fenarray);
  else
  {
   alert('Error in the list of lines');
   return false;
  }
 }
 else form.a.value = fenarray2moviediagram();
 form.f.value = document.getElementById('flipbox').checked ? '1' : '';
 var arco = 0;
 if (form.showarrows.checked) arco += 1;
 if (form.showcoordinates.checked) arco += 2;
 form.q.value = arco;
 form.method = form.linktoanimateddiagram.checked ? 'get' : 'post';
 form.submit();
}
function fenarray2moviediagram(fenarray)
{
 if (fenarray == null) fenarray = FENarray;
 var ret = '';
 for (var i=0; i<fenarray.length; i++)
 {
  var a = fenarray[i];
  a = a.split(' ')[0];
  a = replace(a,'/','H');
  ret += a;
  if (i+1<fenarray.length) ret += '_';
 }
 return ret;
}

function refreshanimagram() // sets the form without submitting, refreshes gif url
{
 var form = document.getElementById('animagramform');
 var fenarray = text2fens_emptyboards();
 form.a.value = (fenarray.length > 0) ? fenarray2moviediagram(fenarray) : fenarray2moviediagram();
 form.f.value = document.getElementById('flipbox').checked ? '1' : '';
 var arco = 0;
 if (form.showarrows.checked) arco += 1;
 if (form.showcoordinates.checked) arco += 2;
 form.q.value = arco;
 form.method = form.linktoanimateddiagram.checked ? 'get' : 'post';
 var src = 'https://chessdiagram.online/arrowgram.php?';
 var delay = parseInt(el('delay').value);
 if (isNaN(delay) || delay <= 0) { delay = '120'; el('delay').value = delay; }
 src += 'a='+form.a.value + '&f='+form.f.value + '&q='+form.q.value + '&de='+delay;
 el('animagramimg').src = src;
 undecorate(); // to prevent positioned arrows from showing in the diagram tab
 return src;
}

// setup

/* API
function initSelectBoard(name,width,height,flip,start,style,size)
function setpoza(name,poza)
function getpoza(name)
function isSelectBoardFlipped(name)
function piecestyle(name)
function squaresize_selectboard(name)
function refresh_selectboard(name,style,size,flip)
selectboard_positionchange(name) is called whenever position on the selectboard is changed
*/
function selectboard_positionchange(name) { return; }
function detailschange() { return; } 
function emptyboard()
{document.getElementById('whiteOO').checked = false; document.getElementById('whiteOOO').checked = false;
 document.getElementById('blackOO').checked = false; document.getElementById('blackOOO').checked = false;
 setpoza("selectboard","empty"); selectboard_positionchange("selectboard");
}
function startboard()
{document.getElementById('whiteOO').checked = true; document.getElementById('whiteOOO').checked = true;
 document.getElementById('blackOO').checked = true; document.getElementById('blackOOO').checked = true;
 document.getElementById('whitestarts').checked = true; setpoza("selectboard","start");
 selectboard_positionchange("selectboard");
}
function castling_from_fen(fen)
{
 var a = (fen.split(' '))[2];
 if (!a) return [false,false,false,false];
 var whiteOO = false, whiteOOO = false, blackOO = false, blackOOO = false;
 if (a.charAt(0)=='K') { whiteOO = true; a = a.substr(1,a.length); }
 if (a.charAt(0)=='Q') { whiteOOO = true; a = a.substr(1,a.length); }
 if (a.charAt(0)=='k') { blackOO = true; a = a.substr(1,a.length); }
 if (a.charAt(0)=='q') { blackOOO = true; a = a.substr(1,a.length); }
 return [whiteOO, whiteOOO, blackOO, blackOOO];
}
function setcastling(a)
{
 document.getElementById('whiteOO').checked = a[0];
 document.getElementById('whiteOOO').checked = a[1];
 document.getElementById('blackOO').checked = a[2];
 document.getElementById('blackOOO').checked = a[3];
}

function entersetup()
{
 setpoza('selectboard',pozafromFEN(GetFen()));
 var flip = document.getElementById('flipbox').checked;
 refresh_selectboard('selectboard',piecestyle('selectboard'),squaresize_selectboard('selectboard'),flip);
 var whitetomove = GetFen().indexOf(' w ')>0; 
 document.getElementById('whitestarts').checked = whitetomove;
 document.getElementById('blackstarts').checked = !whitetomove;
 setcastling( castling_from_fen(GetFen()) );
 document.getElementById("setup").style.display="block";
}
function make_castling_fen_part(poza)
{
 var height = 8;
 var a1 = poza[1];
 var h1 = poza[8];
 var e1 = poza[5];
 var a8 = poza[(height-1)*8+1];
 var h8 = poza[(height-1)*8+8];
 var e8 = poza[(height-1)*8+5];
 
 var whiteOO = document.getElementById('whiteOO').checked;
 var whiteOOO = document.getElementById('whiteOOO').checked;
 var blackOO = document.getElementById('blackOO').checked;
 var blackOOO = document.getElementById('blackOOO').checked;

 var castling = "";
 if (whiteOO && e1=='K' && h1=='R') castling += "K";
 if (whiteOOO && e1=='K' && a1=='R') castling += "Q";
 if (blackOO && e8=='k' && h8=='r') castling += "k";
 if (blackOOO && e8=='k' && a8=='r') castling += "q";
 if (castling == "") castling = "-";
 return castling;
}

function getsetupfen()
{
 var poza = getpoza('selectboard');
 var width = 8, height = 8;
 var blacktomove = document.getElementById('blackstarts').checked;
 var x,y,c,n=0,fen="";
 for (y=height; y>=1; y--)
 {
  for (x=1; x<=width; x++)
  {
   c = poza.charAt((y-1)*width + x);
   if (c == '_') { n++; }
   else
   {
    if (n > 0) { fen += n; n = 0; }
    fen += c;
   }
  }
  if (n > 0) { fen += n; n=0; }
  if (y>1) fen += "/";
 }

 if (blacktomove) fen += " b "; else fen += " w ";

 fen += make_castling_fen_part(poza);

 fen += " -"; // no enpassant
 fen += " 0 1"; // move count
 return fen;
}

function oksetup()
{
 document.getElementById("setup").style.display="none";
 document.getElementById('FenTextBox').value = getsetupfen();
 initfromabsorbfen();
 el('movelimit').value = '0';
 el('movelimit').style.outline = 'thick solid red';
 setTimeout(function(){el('movelimit').style.outline = 'none';},7000);
}



function logline()
{
 var prefix = '//www.apronus.com/chess/stat/stat-passive.php?';
 var ruchy =  encodeURIComponent( document.getElementById('PgnTextBox').value );
 var ruchyurl = encodeURIComponent( passivelogURL() );
 var ref = encodeURIComponent( document.referrer );
 (new Image).src = prefix + 'm='+ruchy + '&u=' + ruchyurl + '&r=' + ref;
}

function getscore()
{
 var pv = document.getElementById('output').innerHTML;
 var sc = pv.split(' ')[1];
 var score = sc.substr(6);
 return parseInt(score);
}

function reactpassively()
{
 //logline();
 if (Value != '') { gurupassive_1(); return; }
 if (g_inCheck) { forcemove(); return; }
 makenullmove();
 var a = matelines(1);
 makenullmove();
 if (a.length > 0) { forcemove(); return; }
 const ms = document.getElementById('TimePerMove').value;
 enginethinkingstyle();
 setTimeout(perform_nullmove, ms);
}
function perform_nullmove()
{
 playnullmove();
 userthinkingstyle();
}

/*
// This works fine but it used to crash my old laptop when I first wrote it years ago
// so I wrote the version below (2022-07-20) which works without UIAnalyzeToggle.
function gurupassive_1()
{
 if (GameOver) return;
 if (g_inCheck) { forcemove(); return; }
 makenullmove();
 UIAnalyzeToggle();
 setTimeout("gurupassive_2()", Math.floor(g_timeout / 1));
}
function gurupassive_2()
{
 var V = parseInt(Value);
 if (isNaN(V)) V = 0;

 UIAnalyzeToggle();
 var score = getscore();
 makenullmove();
 if (score > V)
 {
  //var safe = g_timeout;
  //g_timeout = Math.floor(g_timeout / 2);
  forcemove();
  //g_timeout = safe;
  return;
 }
 else playnullmove(); 
}
*/
function gurupassive_1()
{
 if (GameOver) return;
 if (g_inCheck) { forcemove(); return; }
 enginethinkingstyle();
 makenullmove();
 setTimeout("Search(gurupassive_2, 99, null);",0);
}
function gurupassive_2(bestMove, value, timeTaken, ply)
{
 var V = parseInt(Value);
 if (isNaN(V)) V = 0;
 makenullmove();
 if (value > V)
 {
  forcemove();
  return;
 }
 else
 {
  playnullmove();
  userthinkingstyle();
 }
}

// =====================================================
// 2018-09-12 adding Animacja button to puzzle solving interface

function fenarrayfromnull()
{
 var fenarray = [], FENs = zadanie[8], ilelines = FENs.length;
 for (var i=0; i < ilelines; i++)
 {
  fenarray.push(zadanie[0]); // startfen
  var fensline = FENs[i], ilemoves = fensline.length;
  for (var n=0; n < ilemoves; n++) fenarray.push(fensline[n]);
  fenarray.push('8/8/8/8/8/8/8/8 z - - 0 1');
 }
 return fenarray;
}

function lines2fens(lines)
{
	return fenarrayfromnull();
 var a = lines; if (a.length == 0) return [];
 var saveruchy = sameruchyarray(document.getElementById('PgnTextBox').value);
 var fens = [];
 for (var i=0; i<a.length; i++) if (a[i])
 {
  var sameruchy = tylkoruchy(a[i]);
  if (sameruchy)
  { 
   var line = sameruchy.split('_'); var wariant = wariant2fens(line);
   if (wariant) fens = (fens.concat(wariant)).concat('8/8/8/8/8/8/8/8 w - - 0 1');
  }
 }
 resetgarbochess(saveruchy);
 return fens;
}

function lines2diagrams(lines,niema,flip)
{
	return solutiondiagrams(niema, flip);
 var fenarray = lines2fens(lines);
 var ile = fenarray.length; if (ile == 0) return [];
 var pozas = [];
 for (var i=0; i < ile; i++)
 {
  //var poza = pozafromFEN_svgram(fenarray[i],8,8);
  var poza = pozafromFENtokens(fenarray[i]);
  if (niema) for (var j=0; j < niema.length; j++) poza = replace(poza,niema[j],'_');
  pozas.push(poza);
 }
 ile = pozas.length;
 var arrows = arrowsarray(pozas,8,8);
 var diagrams = [];
 for (var i=0; i < ile; i++)
 {
  var poza = pozas[i];
  var size = 27, kwadraciki = [];
  var arrow = ''; if (arrows[i].length) arrow = arrows[i][0];
  var whitetomove = false, blacktomove = false;
  var diagram = rimgram(poza,8,8,flip,size,'merida',darkR,darkG,darkB,lightR,lightG,lightB,kwadraciki,arrow,'transparent','rgb(150,150,150)','black',whitetomove,blacktomove);
  diagrams.push(diagram);
 }
 return diagrams;
}

function lines2animasvg(lines,niema,flip)
{
	function keyframesdiagram(n,ile)
	{
	 var a = '@keyframes diagram'+n+' ';
	 if (n==0) return a + '{ 0% {opacity:1;} 100% {opacitity:1;} }\n';
	 a += '{ 0% {opacity:0;}';
	 a += ' '+((n/ile)*100)+'% {opacity:1;}';
	 a += ' 100% {opacity:1;} }\n';
	 return a;
	}
 var jedenilesekund = 1.2; if (niema) jedenilesekund = 0.6;
 var diagrams = solutiondiagrams(niema,flip); //lines2diagrams(lines,niema,flip);
 var animasvg = svgelement('svg'); animasvg.setAttribute('width','330'); animasvg.setAttribute('height','330');
 var st = newel('style'); animasvg.appendChild(st);
 for (i=0, ile=diagrams.length; i<ile; i++)
 {
	 st.innerHTML += keyframesdiagram(i,ile);
	 var diagram = diagrams[i];
	 diagram.style.animationName = 'diagram'+i; diagram.style.animationDuration = jedenilesekund*ile+'s';
	 diagram.style.animationIterationCount = 'infinite'; diagram.style.animationTimingFunction = 'step-end';
	 diagram.style.position = 'absolute'; diagram.style.left = '0'; diagram.style.top = '0';
	 diagram.setAttribute('width','100%'); diagram.setAttribute('height','100%');
	 animasvg.appendChild(diagram);
 }
 return animasvg;
}

function animactor(svg)
{
 var width = window.innerWidth, height = window.innerHeight, size = (width < height) ? width : height;
 var border = size*0.1;
 var anima = svg.cloneNode(true);
 anima.style.cursor = 'not-allowed'; anima.setAttribute('onclick','return true;');
 anima.style.width = (size - 2*border)+'px'; anima.style.height = (size - 2*border)+'px';
 anima.style.verticalAlign = 'middle';
 anima.style.border = border + 'px solid #ddd'; anima.style.borderRadius = border + 'px';
 anima.style.background = '#ddd';
 var ov = newel('div'); ov.id = 'animaoverlay'; ov.style.position = 'absolute'; ov.style.zIndex = '700';
 ov.style.left = '0'; ov.style.top = '0';
 ov.style.width = window.innerWidth + 'px'; ov.style.width = '100%';
 ov.style.height = window.innerHeight + 'px'; ov.style.height = '100%';
 ov.style.cursor = 'not-allowed'; ov.style.background = 'rgba(255,255,255,0.5)';
 ov.style.textAlign = 'center';
 ov.setAttribute('onclick','document.body.removeChild(this);');
 var zamknij = newel('div'); var zamknijbutton = newel('button'); zamknij.appendChild(zamknijbutton);
 zamknijbutton.innerHTML = CloseAnimationText()+' &#10060;'; zamknijbutton.style.cursor = 'pointer';
 zamknij.style.position = 'absolute'; zamknij.style.top = '0'; zamknij.style.right = '0'; zamknij.style.width = '100%';
 ov.appendChild(zamknij); ov.appendChild(anima); document.body.appendChild(ov);
}

function ilemoves()
{
 var wariant = el('PgnTextBox').value;
 if (wariant == '') return 0;
 wariant = tylkoruchy(wariant);
 var ilehalfmoves = wariant.split('_').length;
 return (ilehalfmoves % 2) ? ((1+ilehalfmoves)/2) : (ilehalfmoves/2);
}

function showAnimacja()
{
 galogg('showme',ilemoves());
 if (encodedlines.length == 1 && niema == '') { show1diagrams(); return; }
 var oklines = zadanie[1];
 var anima = lines2animasvg(oklines,niema,flipbox.checked);
 animactor(anima);
}

function onemovediagrams()
{
 if (encodedlines.length != 1) { console.log('not one move'); return null; }
 var fenarray = lines2fens(zadanie[1]);
 var ile = fenarray.length; if (ile != 3) { console.log('exactly 3 fens expected'); return null;  }
 var pozas = [];
 for (var i=0; i <= 1; i++) pozas.push( pozafromFEN_svgram(fenarray[i],8,8) );
 var arrows = arrowsarray(pozas,8,8);
 var arrow = ''; if (arrows[1].length) arrow = arrows[1][0]; arrow = arrow.replace('Q128Q128Q128','Q255Q0Q0');
 var diagrams = [];
 for (var i=0; i <= 1; i++)
 {
  var poza = pozas[i];
  var whitetomove = (fenarray[i].indexOf(' w ')>-1), blacktomove = (fenarray[i].indexOf(' b ')>-1);
  var size = 50, kwadraciki = '';
  if (i==1 && (zadanie[1][0].indexOf('#')>-1))
  {
	  var king = (whitetomove) ? 'K' : 'k';
	  for (var x=1; x <= 8; x++) for (var y=1; y <= 8; y++)
		  if (poza[squareIndex(x,y,8,8,false)] == king )
			  kwadraciki = ''+x+'Q'+y+'Q255Q0Q0';
  }
  var flip = flipbox.checked; if (i==1) flip = !flip;
  var diagram = rimgram(poza,8,8,flip,size,'merida',darkR,darkG,darkB,lightR,lightG,lightB,kwadraciki,arrow,'transparent','rgb(150,150,150)','black',whitetomove,blacktomove);
  diagrams.push(diagram);
 }
 return diagrams;
}

function imageprojector(img)
{
 var imgborder = 32;
 img.style.background = '#ddd'; img.style.border = imgborder + 'px solid #ddd';
 img.style.maxWidth = window.innerWidth - 2*imgborder; img.style.maxHeight = window.innerHeight - 2*imgborder;
 var ov = newel('div'); ov.id = 'animaoverlay'; ov.style.position = 'fixed';
 ov.style.left = '0'; ov.style.top = '0'; ov.style.width = '100%'; ov.style.height = '100%';
 ov.style.background = '#ddd'; ov.style.textAlign = 'center'; ov.style.cursor = 'not-allowed';
 ov.setAttribute('onclick','document.body.removeChild(this);');
 var zamknij = newel('div'); var zamknijbutton = newel('button'); zamknij.appendChild(zamknijbutton);
 zamknijbutton.style.margin = '3px';
 zamknijbutton.innerHTML = CloseAnimationText()+' &#10060;'; zamknijbutton.style.cursor = 'pointer';
 zamknijbutton.style.backgroundColor = 'white';
 zamknij.style.position = 'absolute'; zamknij.style.top = '0'; zamknij.style.right = '0'; zamknij.style.width = '100%';
 ov.appendChild(zamknij); ov.appendChild(img); document.body.appendChild(ov);
}

function show1diagrams()
{
	function horigrams(d1,d2)
	{
	 var svg = svgelement('svg'); svg.setAttribute('viewBox','0 0 100 50');
	 var svg1 = d1.cloneNode(true), svg2 = d2.cloneNode(true); svg.appendChild(svg1); svg.appendChild(svg2);
	 svg1.setAttribute('width',50); svg1.setAttribute('height',50); svg2.setAttribute('width',50); svg2.setAttribute('height',50);
	 svg2.setAttribute('x',50);
	 return svg;
	}
	function vertigrams(d1,d2)
	{
	 var svg = svgelement('svg'); svg.setAttribute('viewBox','0 0 50 100');
	 var svg1 = d1.cloneNode(true), svg2 = d2.cloneNode(true); svg.appendChild(svg1); svg.appendChild(svg2);
	 svg1.setAttribute('width',50); svg1.setAttribute('height',50); svg2.setAttribute('width',50); svg2.setAttribute('height',50);
	 svg2.setAttribute('y',50);
	 return svg;
	}
 var diagrams = onemovediagrams();
 var diagram = (window.innerWidth < window.innerHeight) ? vertigrams(diagrams[0],diagrams[1]) : horigrams(diagrams[0],diagrams[1]);
 imageprojector(diagram);
}

/*
function animasvgram(lines,niema,flip)
{
 var anima = lines2animasvg(lines,niema,flip);
 anima.setAttribute('onclick','animactor(this);');
 anima.style.cursor = 'pointer';
 return anima;
}

function showEditorAnimation()
{
 var niema = '';
 var query = new URLSearchParams(location.search)
 if (query.has('niemaK')) niema += 'K';
 if (query.has('niemaR')) niema += 'R';
 if (query.has('niemaN')) niema += 'N';
 if (query.has('niemak')) niema += 'k';
 if (query.has('niemar')) niema += 'r';
 if (query.has('nieman')) niema += 'n';
 prettify(); prettify(); var lines = gettext().split('\n');
 var animadoc = 'animasvgram(["';
 animadoc += lines.join('","');
 animadoc += '"],"'+niema+'",'+flipbox.checked+')'; 

 var div = newel('div'); div.id = 'animalog'; document.body.appendChild(div);
 div.style.position = 'absolute'; div.style.background = 'white'; div.style.border = 'thick groove gray';
 div.style.left = '10%'; div.style.right = '10%'; div.style.top = '10%'; div.style.bottom = '10%';
 var randomid = 'a'+Math.floor(Math.random(123555626)+762665626);
 var doc = '<div id='+randomid+' style="display:inline-block"></div>';
 div.innerHTML += doc;
 doc += '<scr'+'ipt>'+randomid+'.appendChild('+animadoc+');</s'+'cript>';
 el(randomid).appendChild(eval(animadoc));
 var zamknij = newel('button'); zamknij.innerHTML = '&#10060;'; zamknij.style.cursor = 'pointer';
 zamknij.setAttribute('onclick','document.body.removeChild(animalog);');
 div.appendChild(zamknij);
 
 var ta = newel('textarea'); div.appendChild(ta); ta.style.width = '90%';  ta.style.height = '7em'; ta.style.marginLeft = '5%';
 ta.value = doc;
}
*/

function solutiondiagrams(niema, flip)
{
 function diagram(poza, arrow)
 {
  const size = 27;
  const kwadraciki = [];
  const whitetomove = false;
  const blacktomove = false;
  return rimgram(poza,8,8,flip,size,'merida',darkR,darkG,darkB,lightR,lightG,lightB,kwadraciki,
   arrow,'transparent','rgb(150,150,150)','black',whitetomove,blacktomove);
 }
 const SANs = zadanie[7];
 const FENs = zadanie[8];
 const garbomoves = zadanie[9];
 const diagrams = [];
 const emptypoza = pozafromFENtokens('8/8/8/8/8/8/8/8 z - - 0 1');
 const startpoza = pozafromFENtokens(zadanie[0]);
 for (let n_line = 0; n_line < FENs.length; n_line++)
 {
  const FENs_line = FENs[n_line];
  diagrams.push(diagram(startpoza, ''));
  for (let n_move = 0; n_move < FENs_line.length; n_move++)
  {
   const FEN = FENs_line[n_move];
   let poza = pozafromFENtokens(FEN);
   if (niema) for (const czego of niema) poza = replace(poza, czego, '_');

   const preFEN = (n_move > 0) ? FENs_line[n_move-1] : zadanie[0];
   const prepoza = (n_move > 0) ? pozafromFENtokens(preFEN) : startpoza;
   const pozas = [prepoza, poza];
   const arrows = arrowsarray(pozas,8,8);
   const arrow = (arrows[1].length) ? arrows[1][0] : '';

   const d = diagram(poza, arrow);

   const SANmove = SANs[n_line][n_move];
   let reason = '';
   if (SANmove.includes('+')) reason = '+';
   if (SANmove.includes('#')) reason = '#';
   if (SANmove.includes('$')) reason = '$';
   if (reason)
   {
    const king = FEN.includes(' w ') ? 'K' : 'k';
    color_theking_in_diagram(d,king,reason);
   }
   diagrams.push(d); 
  }
  diagrams.push(diagram(emptypoza, ''));
 }
 return diagrams;
}



function apronussharerURL()
{
	function czyKorpal()
	{
		var url = parent.location.href;
		if (url.indexOf('https://szachydzieciom.pl/')==0) return true;
		if (url.indexOf('/wizboard/') > -1) return false;
		if (url.indexOf('https://agnes-bruckner.com/klon/')==0) return true;
		return false;
	}
	var ref = '';
	if (puzzleset && czyKorpal())
	{
		var n = puzzleset_currentnumber();
		var query = new URLSearchParams(parent.location.search);
		var id = (query.has('page_id')) ? query.get('page_id') : '';
		var lang = 'en'; if (isPolish()) lang = 'pl'; if (isRussian()) lang = 'ru';
		var prefix = 'https://szachydzieciom.pl/';
		ref = prefix + '?page_id='+id + '&lang='+lang + '#'+n;
	}
	var url = 'https://www.apronus.com/chess/puzzle/'+puzzle;
	if (isPolish()) url += '&lang=pl'; if (isRussian()) url += '&lang=ru';
	if (ref) url += '&ref=' + encodeURIComponent(ref);
	return url;
}

function facebooksharerbutton()
{
	var size = 16;
	var bu = newel('button'); bu.id = 'facebooksharer';
	var img = shareiconimg(); img.style.height = size+'px'; img.style.marginRight = '2px';
	img.style.display = 'inline-block'; img.style.verticalAlign = 'bottom';
	bu.appendChild(img);
	var f = facebookicon(); f.style.width = size+'px'; f.style.height = size+'px';
	f.style.display = 'inline-block'; f.style.verticalAlign = 'bottom';
	bu.appendChild(f);
	var url = apronussharerURL();
	var ga = "galogg('share-facebook','"+url+"');";
	url = 'https://facebook.com/sharer.php?u=' + encodeURIComponent(url);
	bu.setAttribute('onclick','window.open("'+url+'");'+ga);
	bu.style.background = 'white'; bu.style.cursor = 'pointer';
	bu.style.padding = '0px';
	//bu.style.display = 'block'; // removed from below board buttons
	bu.style.margin = '0 1em 0 1em'; // relocated to controls
	return bu;
}

function chesthetica_header_html(number)
{
	var amazonURL = 'https://www.amazon.com/Azlan-Iqbal/e/B07F91L51G';
	var channelURL = 'https://www.youtube.com/c/Chesthetica';
	var videoURL = 'https://chessdiagram.online/chesthetica/' + number + '.mp4';
	var a = newel('a'); a.href = videoURL; a.target = '_blank'; a.innerHTML = 'Solution Video';
	var b = newel('a'); b.href = channelURL; b.target = '_blank'; b.innerHTML = 'Chesthetica YouTube Channel';
	var c = newel('a'); c.href = amazonURL; c.target = '_blank'; c.innerHTML = 'Related Books';
	var h6 = newel('h6');
	if (number) { h6.appendChild(a); h6.appendChild(document.createTextNode(' | ')); }
	h6.appendChild(b); h6.appendChild(document.createTextNode(' | '));
	h6.appendChild(c);
	var div = newel('div'); div.appendChild(h6);
	return div.innerHTML;
}


function findrules(a)
{
	function count(a) { return a.length; }
	function array_push(arr,what) { return arr.push(what); }
	function array_merge(arr1,arr2) { return arr1.concat(arr2); }

	function pogrupuj(linelist)
	{
	 if (count(linelist)==0) return new Array();
	 if (count(linelist)==1) return new Array(linelist);
	 var grupsko = new Array( new Array( linelist[0] ) );
	 for (i=1; i<count(linelist); i++)
	 {
	  var line = linelist[i];
	  var ruch = line[1][0];
	  var dogrupy = -1;
	  for (j=0; j<count(grupsko); j++)
	   if (grupsko[j][0][1][0]==ruch) dogrupy = j;
	  if (dogrupy == -1) array_push(grupsko,new Array(line));
					 else array_push(grupsko[dogrupy],line);
	 }
	 return grupsko;
	}

	function discernrules(linelist)
	{
	 if (count(linelist)<2) return new Array();
	 var grupsko = pogrupuj(linelist);
	 if (count(grupsko)>1)
	 {
	  var rules = new Array();
	  for (var i=0; i<count(grupsko); i++)
	   rules = array_merge(rules,discernrules(grupsko[i]));
	  
	  var ids = new Array();
	  for (var i=0; i<count(grupsko); i++)
	  {
	   ids[i] = new Array();
	   for (j=0; j<count(grupsko[i]); j++)
		array_push(ids[i],grupsko[i][j][0]);
	  }
	  
	  for (i=1; i<count(grupsko); i++)
	   array_push(rules, new Array( ids[0], ids[i] ) );
	  return rules;
	 }
	 
	 var grupa = grupsko[0];
	 for (var i=0; i<count(grupa); i++) (grupa[i][1]).shift();
	 grupsko = pogrupuj(grupa);
	 var rules = new Array();
	 for (var i=0; i<count(grupsko); i++)
	 {
	  for (j=0; j<count(grupsko[i]); j++) (grupsko[i][j][1]).shift();
	  rules = array_merge(rules,discernrules(grupsko[i]));
	 }
	 return rules;
	}

	function duplicatefree(a)
	{
	 var b = new Array();
	 var ile = count(a);
	 if (ile == 0) return b;
	 for (var i=0; i<ile; i++)
	 {
	  var x = a[i];
	  var unique = true;
	  var ileb = count(b);
	  for (j=0; j<ileb; j++) if (x == b[j]) unique = false;
	  if (unique) array_push(b,x);
	 }
	 return b;
	}
	
 a = duplicatefree(a);
 var ile = count(a);
 if (ile == 0) return new Array();
 var linelist = new Array();
 for (var i=0; i<ile; i++)
 {
  array_push(linelist,new Array(i,a[i]));
 }
 return discernrules(linelist);
}
/*! jQuery UI - v1.8.24 - 2012-09-28
* https://github.com/jquery/jquery-ui
* Includes: jquery.ui.core.js
* Copyright (c) 2012 AUTHORS.txt; Licensed MIT, GPL */
(function(a,b){function c(b,c){var e=b.nodeName.toLowerCase();if("area"===e){var f=b.parentNode,g=f.name,h;return!b.href||!g||f.nodeName.toLowerCase()!=="map"?!1:(h=a("img[usemap=#"+g+"]")[0],!!h&&d(h))}return(/input|select|textarea|button|object/.test(e)?!b.disabled:"a"==e?b.href||c:c)&&d(b)}function d(b){return!a(b).parents().andSelf().filter(function(){return a.curCSS(this,"visibility")==="hidden"||a.expr.filters.hidden(this)}).length}a.ui=a.ui||{};if(a.ui.version)return;a.extend(a.ui,{version:"1.8.24",keyCode:{ALT:18,BACKSPACE:8,CAPS_LOCK:20,COMMA:188,COMMAND:91,COMMAND_LEFT:91,COMMAND_RIGHT:93,CONTROL:17,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,INSERT:45,LEFT:37,MENU:93,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SHIFT:16,SPACE:32,TAB:9,UP:38,WINDOWS:91}}),a.fn.extend({propAttr:a.fn.prop||a.fn.attr,_focus:a.fn.focus,focus:function(b,c){return typeof b=="number"?this.each(function(){var d=this;setTimeout(function(){a(d).focus(),c&&c.call(d)},b)}):this._focus.apply(this,arguments)},scrollParent:function(){var b;return a.browser.msie&&/(static|relative)/.test(this.css("position"))||/absolute/.test(this.css("position"))?b=this.parents().filter(function(){return/(relative|absolute|fixed)/.test(a.curCSS(this,"position",1))&&/(auto|scroll)/.test(a.curCSS(this,"overflow",1)+a.curCSS(this,"overflow-y",1)+a.curCSS(this,"overflow-x",1))}).eq(0):b=this.parents().filter(function(){return/(auto|scroll)/.test(a.curCSS(this,"overflow",1)+a.curCSS(this,"overflow-y",1)+a.curCSS(this,"overflow-x",1))}).eq(0),/fixed/.test(this.css("position"))||!b.length?a(document):b},zIndex:function(c){if(c!==b)return this.css("zIndex",c);if(this.length){var d=a(this[0]),e,f;while(d.length&&d[0]!==document){e=d.css("position");if(e==="absolute"||e==="relative"||e==="fixed"){f=parseInt(d.css("zIndex"),10);if(!isNaN(f)&&f!==0)return f}d=d.parent()}}return 0},disableSelection:function(){return this.bind((a.support.selectstart?"selectstart":"mousedown")+".ui-disableSelection",function(a){a.preventDefault()})},enableSelection:function(){return this.unbind(".ui-disableSelection")}}),a("<a>").outerWidth(1).jquery||a.each(["Width","Height"],function(c,d){function h(b,c,d,f){return a.each(e,function(){c-=parseFloat(a.curCSS(b,"padding"+this,!0))||0,d&&(c-=parseFloat(a.curCSS(b,"border"+this+"Width",!0))||0),f&&(c-=parseFloat(a.curCSS(b,"margin"+this,!0))||0)}),c}var e=d==="Width"?["Left","Right"]:["Top","Bottom"],f=d.toLowerCase(),g={innerWidth:a.fn.innerWidth,innerHeight:a.fn.innerHeight,outerWidth:a.fn.outerWidth,outerHeight:a.fn.outerHeight};a.fn["inner"+d]=function(c){return c===b?g["inner"+d].call(this):this.each(function(){a(this).css(f,h(this,c)+"px")})},a.fn["outer"+d]=function(b,c){return typeof b!="number"?g["outer"+d].call(this,b):this.each(function(){a(this).css(f,h(this,b,!0,c)+"px")})}}),a.extend(a.expr[":"],{data:a.expr.createPseudo?a.expr.createPseudo(function(b){return function(c){return!!a.data(c,b)}}):function(b,c,d){return!!a.data(b,d[3])},focusable:function(b){return c(b,!isNaN(a.attr(b,"tabindex")))},tabbable:function(b){var d=a.attr(b,"tabindex"),e=isNaN(d);return(e||d>=0)&&c(b,!e)}}),a(function(){var b=document.body,c=b.appendChild(c=document.createElement("div"));c.offsetHeight,a.extend(c.style,{minHeight:"100px",height:"auto",padding:0,borderWidth:0}),a.support.minHeight=c.offsetHeight===100,a.support.selectstart="onselectstart"in c,b.removeChild(c).style.display="none"}),a.curCSS||(a.curCSS=a.css),a.extend(a.ui,{plugin:{add:function(b,c,d){var e=a.ui[b].prototype;for(var f in d)e.plugins[f]=e.plugins[f]||[],e.plugins[f].push([c,d[f]])},call:function(a,b,c){var d=a.plugins[b];if(!d||!a.element[0].parentNode)return;for(var e=0;e<d.length;e++)a.options[d[e][0]]&&d[e][1].apply(a.element,c)}},contains:function(a,b){return document.compareDocumentPosition?a.compareDocumentPosition(b)&16:a!==b&&a.contains(b)},hasScroll:function(b,c){if(a(b).css("overflow")==="hidden")return!1;var d=c&&c==="left"?"scrollLeft":"scrollTop",e=!1;return b[d]>0?!0:(b[d]=1,e=b[d]>0,b[d]=0,e)},isOverAxis:function(a,b,c){return a>b&&a<b+c},isOver:function(b,c,d,e,f,g){return a.ui.isOverAxis(b,d,f)&&a.ui.isOverAxis(c,e,g)}})})(jQuery);;/*! jQuery UI - v1.8.24 - 2012-09-28
* https://github.com/jquery/jquery-ui
* Includes: jquery.ui.widget.js
* Copyright (c) 2012 AUTHORS.txt; Licensed MIT, GPL */
(function(a,b){if(a.cleanData){var c=a.cleanData;a.cleanData=function(b){for(var d=0,e;(e=b[d])!=null;d++)try{a(e).triggerHandler("remove")}catch(f){}c(b)}}else{var d=a.fn.remove;a.fn.remove=function(b,c){return this.each(function(){return c||(!b||a.filter(b,[this]).length)&&a("*",this).add([this]).each(function(){try{a(this).triggerHandler("remove")}catch(b){}}),d.call(a(this),b,c)})}}a.widget=function(b,c,d){var e=b.split(".")[0],f;b=b.split(".")[1],f=e+"-"+b,d||(d=c,c=a.Widget),a.expr[":"][f]=function(c){return!!a.data(c,b)},a[e]=a[e]||{},a[e][b]=function(a,b){arguments.length&&this._createWidget(a,b)};var g=new c;g.options=a.extend(!0,{},g.options),a[e][b].prototype=a.extend(!0,g,{namespace:e,widgetName:b,widgetEventPrefix:a[e][b].prototype.widgetEventPrefix||b,widgetBaseClass:f},d),a.widget.bridge(b,a[e][b])},a.widget.bridge=function(c,d){a.fn[c]=function(e){var f=typeof e=="string",g=Array.prototype.slice.call(arguments,1),h=this;return e=!f&&g.length?a.extend.apply(null,[!0,e].concat(g)):e,f&&e.charAt(0)==="_"?h:(f?this.each(function(){var d=a.data(this,c),f=d&&a.isFunction(d[e])?d[e].apply(d,g):d;if(f!==d&&f!==b)return h=f,!1}):this.each(function(){var b=a.data(this,c);b?b.option(e||{})._init():a.data(this,c,new d(e,this))}),h)}},a.Widget=function(a,b){arguments.length&&this._createWidget(a,b)},a.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",options:{disabled:!1},_createWidget:function(b,c){a.data(c,this.widgetName,this),this.element=a(c),this.options=a.extend(!0,{},this.options,this._getCreateOptions(),b);var d=this;this.element.bind("remove."+this.widgetName,function(){d.destroy()}),this._create(),this._trigger("create"),this._init()},_getCreateOptions:function(){return a.metadata&&a.metadata.get(this.element[0])[this.widgetName]},_create:function(){},_init:function(){},destroy:function(){this.element.unbind("."+this.widgetName).removeData(this.widgetName),this.widget().unbind("."+this.widgetName).removeAttr("aria-disabled").removeClass(this.widgetBaseClass+"-disabled "+"ui-state-disabled")},widget:function(){return this.element},option:function(c,d){var e=c;if(arguments.length===0)return a.extend({},this.options);if(typeof c=="string"){if(d===b)return this.options[c];e={},e[c]=d}return this._setOptions(e),this},_setOptions:function(b){var c=this;return a.each(b,function(a,b){c._setOption(a,b)}),this},_setOption:function(a,b){return this.options[a]=b,a==="disabled"&&this.widget()[b?"addClass":"removeClass"](this.widgetBaseClass+"-disabled"+" "+"ui-state-disabled").attr("aria-disabled",b),this},enable:function(){return this._setOption("disabled",!1)},disable:function(){return this._setOption("disabled",!0)},_trigger:function(b,c,d){var e,f,g=this.options[b];d=d||{},c=a.Event(c),c.type=(b===this.widgetEventPrefix?b:this.widgetEventPrefix+b).toLowerCase(),c.target=this.element[0],f=c.originalEvent;if(f)for(e in f)e in c||(c[e]=f[e]);return this.element.trigger(c,d),!(a.isFunction(g)&&g.call(this.element[0],c,d)===!1||c.isDefaultPrevented())}}})(jQuery);;/*! jQuery UI - v1.8.24 - 2012-09-28
* https://github.com/jquery/jquery-ui
* Includes: jquery.ui.mouse.js
* Copyright (c) 2012 AUTHORS.txt; Licensed MIT, GPL */
(function(a,b){var c=!1;a(document).mouseup(function(a){c=!1}),a.widget("ui.mouse",{options:{cancel:":input,option",distance:1,delay:0},_mouseInit:function(){var b=this;this.element.bind("mousedown."+this.widgetName,function(a){return b._mouseDown(a)}).bind("click."+this.widgetName,function(c){if(!0===a.data(c.target,b.widgetName+".preventClickEvent"))return a.removeData(c.target,b.widgetName+".preventClickEvent"),c.stopImmediatePropagation(),!1}),this.started=!1},_mouseDestroy:function(){this.element.unbind("."+this.widgetName),this._mouseMoveDelegate&&a(document).unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate)},_mouseDown:function(b){if(c)return;this._mouseStarted&&this._mouseUp(b),this._mouseDownEvent=b;var d=this,e=b.which==1,f=typeof this.options.cancel=="string"&&b.target.nodeName?a(b.target).closest(this.options.cancel).length:!1;if(!e||f||!this._mouseCapture(b))return!0;this.mouseDelayMet=!this.options.delay,this.mouseDelayMet||(this._mouseDelayTimer=setTimeout(function(){d.mouseDelayMet=!0},this.options.delay));if(this._mouseDistanceMet(b)&&this._mouseDelayMet(b)){this._mouseStarted=this._mouseStart(b)!==!1;if(!this._mouseStarted)return b.preventDefault(),!0}return!0===a.data(b.target,this.widgetName+".preventClickEvent")&&a.removeData(b.target,this.widgetName+".preventClickEvent"),this._mouseMoveDelegate=function(a){return d._mouseMove(a)},this._mouseUpDelegate=function(a){return d._mouseUp(a)},a(document).bind("mousemove."+this.widgetName,this._mouseMoveDelegate).bind("mouseup."+this.widgetName,this._mouseUpDelegate),b.preventDefault(),c=!0,!0},_mouseMove:function(b){return!a.browser.msie||document.documentMode>=9||!!b.button?this._mouseStarted?(this._mouseDrag(b),b.preventDefault()):(this._mouseDistanceMet(b)&&this._mouseDelayMet(b)&&(this._mouseStarted=this._mouseStart(this._mouseDownEvent,b)!==!1,this._mouseStarted?this._mouseDrag(b):this._mouseUp(b)),!this._mouseStarted):this._mouseUp(b)},_mouseUp:function(b){return a(document).unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate),this._mouseStarted&&(this._mouseStarted=!1,b.target==this._mouseDownEvent.target&&a.data(b.target,this.widgetName+".preventClickEvent",!0),this._mouseStop(b)),!1},_mouseDistanceMet:function(a){return Math.max(Math.abs(this._mouseDownEvent.pageX-a.pageX),Math.abs(this._mouseDownEvent.pageY-a.pageY))>=this.options.distance},_mouseDelayMet:function(a){return this.mouseDelayMet},_mouseStart:function(a){},_mouseDrag:function(a){},_mouseStop:function(a){},_mouseCapture:function(a){return!0}})})(jQuery);;/*! jQuery UI - v1.8.24 - 2012-09-28
* https://github.com/jquery/jquery-ui
* Includes: jquery.ui.position.js
* Copyright (c) 2012 AUTHORS.txt; Licensed MIT, GPL */
(function(a,b){a.ui=a.ui||{};var c=/left|center|right/,d=/top|center|bottom/,e="center",f={},g=a.fn.position,h=a.fn.offset;a.fn.position=function(b){if(!b||!b.of)return g.apply(this,arguments);b=a.extend({},b);var h=a(b.of),i=h[0],j=(b.collision||"flip").split(" "),k=b.offset?b.offset.split(" "):[0,0],l,m,n;return i.nodeType===9?(l=h.width(),m=h.height(),n={top:0,left:0}):i.setTimeout?(l=h.width(),m=h.height(),n={top:h.scrollTop(),left:h.scrollLeft()}):i.preventDefault?(b.at="left top",l=m=0,n={top:b.of.pageY,left:b.of.pageX}):(l=h.outerWidth(),m=h.outerHeight(),n=h.offset()),a.each(["my","at"],function(){var a=(b[this]||"").split(" ");a.length===1&&(a=c.test(a[0])?a.concat([e]):d.test(a[0])?[e].concat(a):[e,e]),a[0]=c.test(a[0])?a[0]:e,a[1]=d.test(a[1])?a[1]:e,b[this]=a}),j.length===1&&(j[1]=j[0]),k[0]=parseInt(k[0],10)||0,k.length===1&&(k[1]=k[0]),k[1]=parseInt(k[1],10)||0,b.at[0]==="right"?n.left+=l:b.at[0]===e&&(n.left+=l/2),b.at[1]==="bottom"?n.top+=m:b.at[1]===e&&(n.top+=m/2),n.left+=k[0],n.top+=k[1],this.each(function(){var c=a(this),d=c.outerWidth(),g=c.outerHeight(),h=parseInt(a.curCSS(this,"marginLeft",!0))||0,i=parseInt(a.curCSS(this,"marginTop",!0))||0,o=d+h+(parseInt(a.curCSS(this,"marginRight",!0))||0),p=g+i+(parseInt(a.curCSS(this,"marginBottom",!0))||0),q=a.extend({},n),r;b.my[0]==="right"?q.left-=d:b.my[0]===e&&(q.left-=d/2),b.my[1]==="bottom"?q.top-=g:b.my[1]===e&&(q.top-=g/2),f.fractions||(q.left=Math.round(q.left),q.top=Math.round(q.top)),r={left:q.left-h,top:q.top-i},a.each(["left","top"],function(c,e){a.ui.position[j[c]]&&a.ui.position[j[c]][e](q,{targetWidth:l,targetHeight:m,elemWidth:d,elemHeight:g,collisionPosition:r,collisionWidth:o,collisionHeight:p,offset:k,my:b.my,at:b.at})}),a.fn.bgiframe&&c.bgiframe(),c.offset(a.extend(q,{using:b.using}))})},a.ui.position={fit:{left:function(b,c){var d=a(window),e=c.collisionPosition.left+c.collisionWidth-d.width()-d.scrollLeft();b.left=e>0?b.left-e:Math.max(b.left-c.collisionPosition.left,b.left)},top:function(b,c){var d=a(window),e=c.collisionPosition.top+c.collisionHeight-d.height()-d.scrollTop();b.top=e>0?b.top-e:Math.max(b.top-c.collisionPosition.top,b.top)}},flip:{left:function(b,c){if(c.at[0]===e)return;var d=a(window),f=c.collisionPosition.left+c.collisionWidth-d.width()-d.scrollLeft(),g=c.my[0]==="left"?-c.elemWidth:c.my[0]==="right"?c.elemWidth:0,h=c.at[0]==="left"?c.targetWidth:-c.targetWidth,i=-2*c.offset[0];b.left+=c.collisionPosition.left<0?g+h+i:f>0?g+h+i:0},top:function(b,c){if(c.at[1]===e)return;var d=a(window),f=c.collisionPosition.top+c.collisionHeight-d.height()-d.scrollTop(),g=c.my[1]==="top"?-c.elemHeight:c.my[1]==="bottom"?c.elemHeight:0,h=c.at[1]==="top"?c.targetHeight:-c.targetHeight,i=-2*c.offset[1];b.top+=c.collisionPosition.top<0?g+h+i:f>0?g+h+i:0}}},a.offset.setOffset||(a.offset.setOffset=function(b,c){/static/.test(a.curCSS(b,"position"))&&(b.style.position="relative");var d=a(b),e=d.offset(),f=parseInt(a.curCSS(b,"top",!0),10)||0,g=parseInt(a.curCSS(b,"left",!0),10)||0,h={top:c.top-e.top+f,left:c.left-e.left+g};"using"in c?c.using.call(b,h):d.css(h)},a.fn.offset=function(b){var c=this[0];return!c||!c.ownerDocument?null:b?a.isFunction(b)?this.each(function(c){a(this).offset(b.call(this,c,a(this).offset()))}):this.each(function(){a.offset.setOffset(this,b)}):h.call(this)}),a.curCSS||(a.curCSS=a.css),function(){var b=document.getElementsByTagName("body")[0],c=document.createElement("div"),d,e,g,h,i;d=document.createElement(b?"div":"body"),g={visibility:"hidden",width:0,height:0,border:0,margin:0,background:"none"},b&&a.extend(g,{position:"absolute",left:"-1000px",top:"-1000px"});for(var j in g)d.style[j]=g[j];d.appendChild(c),e=b||document.documentElement,e.insertBefore(d,e.firstChild),c.style.cssText="position: absolute; left: 10.7432222px; top: 10.432325px; height: 30px; width: 201px;",h=a(c).offset(function(a,b){return b}).offset(),d.innerHTML="",e.removeChild(d),i=h.top+h.left+(b?2e3:0),f.fractions=i>21&&i<22}()})(jQuery);;/*! jQuery UI - v1.8.24 - 2012-09-28
* https://github.com/jquery/jquery-ui
* Includes: jquery.ui.draggable.js
* Copyright (c) 2012 AUTHORS.txt; Licensed MIT, GPL */
(function(a,b){a.widget("ui.draggable",a.ui.mouse,{widgetEventPrefix:"drag",options:{addClasses:!0,appendTo:"parent",axis:!1,connectToSortable:!1,containment:!1,cursor:"auto",cursorAt:!1,grid:!1,handle:!1,helper:"original",iframeFix:!1,opacity:!1,refreshPositions:!1,revert:!1,revertDuration:500,scope:"default",scroll:!0,scrollSensitivity:20,scrollSpeed:20,snap:!1,snapMode:"both",snapTolerance:20,stack:!1,zIndex:!1},_create:function(){this.options.helper=="original"&&!/^(?:r|a|f)/.test(this.element.css("position"))&&(this.element[0].style.position="relative"),this.options.addClasses&&this.element.addClass("ui-draggable"),this.options.disabled&&this.element.addClass("ui-draggable-disabled"),this._mouseInit()},destroy:function(){if(!this.element.data("draggable"))return;return this.element.removeData("draggable").unbind(".draggable").removeClass("ui-draggable ui-draggable-dragging ui-draggable-disabled"),this._mouseDestroy(),this},_mouseCapture:function(b){var c=this.options;return this.helper||c.disabled||a(b.target).is(".ui-resizable-handle")?!1:(this.handle=this._getHandle(b),this.handle?(c.iframeFix&&a(c.iframeFix===!0?"iframe":c.iframeFix).each(function(){a('<div class="ui-draggable-iframeFix" style="background: #fff;"></div>').css({width:this.offsetWidth+"px",height:this.offsetHeight+"px",position:"absolute",opacity:"0.001",zIndex:1e3}).css(a(this).offset()).appendTo("body")}),!0):!1)},_mouseStart:function(b){var c=this.options;return this.helper=this._createHelper(b),this.helper.addClass("ui-draggable-dragging"),this._cacheHelperProportions(),a.ui.ddmanager&&(a.ui.ddmanager.current=this),this._cacheMargins(),this.cssPosition=this.helper.css("position"),this.scrollParent=this.helper.scrollParent(),this.offset=this.positionAbs=this.element.offset(),this.offset={top:this.offset.top-this.margins.top,left:this.offset.left-this.margins.left},a.extend(this.offset,{click:{left:b.pageX-this.offset.left,top:b.pageY-this.offset.top},parent:this._getParentOffset(),relative:this._getRelativeOffset()}),this.originalPosition=this.position=this._generatePosition(b),this.originalPageX=b.pageX,this.originalPageY=b.pageY,c.cursorAt&&this._adjustOffsetFromHelper(c.cursorAt),c.containment&&this._setContainment(),this._trigger("start",b)===!1?(this._clear(),!1):(this._cacheHelperProportions(),a.ui.ddmanager&&!c.dropBehaviour&&a.ui.ddmanager.prepareOffsets(this,b),this._mouseDrag(b,!0),a.ui.ddmanager&&a.ui.ddmanager.dragStart(this,b),!0)},_mouseDrag:function(b,c){this.position=this._generatePosition(b),this.positionAbs=this._convertPositionTo("absolute");if(!c){var d=this._uiHash();if(this._trigger("drag",b,d)===!1)return this._mouseUp({}),!1;this.position=d.position}if(!this.options.axis||this.options.axis!="y")this.helper[0].style.left=this.position.left+"px";if(!this.options.axis||this.options.axis!="x")this.helper[0].style.top=this.position.top+"px";return a.ui.ddmanager&&a.ui.ddmanager.drag(this,b),!1},_mouseStop:function(b){var c=!1;a.ui.ddmanager&&!this.options.dropBehaviour&&(c=a.ui.ddmanager.drop(this,b)),this.dropped&&(c=this.dropped,this.dropped=!1);var d=this.element[0],e=!1;while(d&&(d=d.parentNode))d==document&&(e=!0);if(!e&&this.options.helper==="original")return!1;if(this.options.revert=="invalid"&&!c||this.options.revert=="valid"&&c||this.options.revert===!0||a.isFunction(this.options.revert)&&this.options.revert.call(this.element,c)){var f=this;a(this.helper).animate(this.originalPosition,parseInt(this.options.revertDuration,10),function(){f._trigger("stop",b)!==!1&&f._clear()})}else this._trigger("stop",b)!==!1&&this._clear();return!1},_mouseUp:function(b){return a("div.ui-draggable-iframeFix").each(function(){this.parentNode.removeChild(this)}),a.ui.ddmanager&&a.ui.ddmanager.dragStop(this,b),a.ui.mouse.prototype._mouseUp.call(this,b)},cancel:function(){return this.helper.is(".ui-draggable-dragging")?this._mouseUp({}):this._clear(),this},_getHandle:function(b){var c=!this.options.handle||!a(this.options.handle,this.element).length?!0:!1;return a(this.options.handle,this.element).find("*").andSelf().each(function(){this==b.target&&(c=!0)}),c},_createHelper:function(b){var c=this.options,d=a.isFunction(c.helper)?a(c.helper.apply(this.element[0],[b])):c.helper=="clone"?this.element.clone().removeAttr("id"):this.element;return d.parents("body").length||d.appendTo(c.appendTo=="parent"?this.element[0].parentNode:c.appendTo),d[0]!=this.element[0]&&!/(fixed|absolute)/.test(d.css("position"))&&d.css("position","absolute"),d},_adjustOffsetFromHelper:function(b){typeof b=="string"&&(b=b.split(" ")),a.isArray(b)&&(b={left:+b[0],top:+b[1]||0}),"left"in b&&(this.offset.click.left=b.left+this.margins.left),"right"in b&&(this.offset.click.left=this.helperProportions.width-b.right+this.margins.left),"top"in b&&(this.offset.click.top=b.top+this.margins.top),"bottom"in b&&(this.offset.click.top=this.helperProportions.height-b.bottom+this.margins.top)},_getParentOffset:function(){this.offsetParent=this.helper.offsetParent();var b=this.offsetParent.offset();this.cssPosition=="absolute"&&this.scrollParent[0]!=document&&a.ui.contains(this.scrollParent[0],this.offsetParent[0])&&(b.left+=this.scrollParent.scrollLeft(),b.top+=this.scrollParent.scrollTop());if(this.offsetParent[0]==document.body||this.offsetParent[0].tagName&&this.offsetParent[0].tagName.toLowerCase()=="html"&&a.browser.msie)b={top:0,left:0};return{top:b.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:b.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)}},_getRelativeOffset:function(){if(this.cssPosition=="relative"){var a=this.element.position();return{top:a.top-(parseInt(this.helper.css("top"),10)||0)+this.scrollParent.scrollTop(),left:a.left-(parseInt(this.helper.css("left"),10)||0)+this.scrollParent.scrollLeft()}}return{top:0,left:0}},_cacheMargins:function(){this.margins={left:parseInt(this.element.css("marginLeft"),10)||0,top:parseInt(this.element.css("marginTop"),10)||0,right:parseInt(this.element.css("marginRight"),10)||0,bottom:parseInt(this.element.css("marginBottom"),10)||0}},_cacheHelperProportions:function(){this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()}},_setContainment:function(){var b=this.options;b.containment=="parent"&&(b.containment=this.helper[0].parentNode);if(b.containment=="document"||b.containment=="window")this.containment=[b.containment=="document"?0:a(window).scrollLeft()-this.offset.relative.left-this.offset.parent.left,b.containment=="document"?0:a(window).scrollTop()-this.offset.relative.top-this.offset.parent.top,(b.containment=="document"?0:a(window).scrollLeft())+a(b.containment=="document"?document:window).width()-this.helperProportions.width-this.margins.left,(b.containment=="document"?0:a(window).scrollTop())+(a(b.containment=="document"?document:window).height()||document.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top];if(!/^(document|window|parent)$/.test(b.containment)&&b.containment.constructor!=Array){var c=a(b.containment),d=c[0];if(!d)return;var e=c.offset(),f=a(d).css("overflow")!="hidden";this.containment=[(parseInt(a(d).css("borderLeftWidth"),10)||0)+(parseInt(a(d).css("paddingLeft"),10)||0),(parseInt(a(d).css("borderTopWidth"),10)||0)+(parseInt(a(d).css("paddingTop"),10)||0),(f?Math.max(d.scrollWidth,d.offsetWidth):d.offsetWidth)-(parseInt(a(d).css("borderLeftWidth"),10)||0)-(parseInt(a(d).css("paddingRight"),10)||0)-this.helperProportions.width-this.margins.left-this.margins.right,(f?Math.max(d.scrollHeight,d.offsetHeight):d.offsetHeight)-(parseInt(a(d).css("borderTopWidth"),10)||0)-(parseInt(a(d).css("paddingBottom"),10)||0)-this.helperProportions.height-this.margins.top-this.margins.bottom],this.relative_container=c}else b.containment.constructor==Array&&(this.containment=b.containment)},_convertPositionTo:function(b,c){c||(c=this.position);var d=b=="absolute"?1:-1,e=this.options,f=this.cssPosition=="absolute"&&(this.scrollParent[0]==document||!a.ui.contains(this.scrollParent[0],this.offsetParent[0]))?this.offsetParent:this.scrollParent,g=/(html|body)/i.test(f[0].tagName);return{top:c.top+this.offset.relative.top*d+this.offset.parent.top*d-(a.browser.safari&&a.browser.version<526&&this.cssPosition=="fixed"?0:(this.cssPosition=="fixed"?-this.scrollParent.scrollTop():g?0:f.scrollTop())*d),left:c.left+this.offset.relative.left*d+this.offset.parent.left*d-(a.browser.safari&&a.browser.version<526&&this.cssPosition=="fixed"?0:(this.cssPosition=="fixed"?-this.scrollParent.scrollLeft():g?0:f.scrollLeft())*d)}},_generatePosition:function(b){var c=this.options,d=this.cssPosition=="absolute"&&(this.scrollParent[0]==document||!a.ui.contains(this.scrollParent[0],this.offsetParent[0]))?this.offsetParent:this.scrollParent,e=/(html|body)/i.test(d[0].tagName),f=b.pageX,g=b.pageY;if(this.originalPosition){var h;if(this.containment){if(this.relative_container){var i=this.relative_container.offset();h=[this.containment[0]+i.left,this.containment[1]+i.top,this.containment[2]+i.left,this.containment[3]+i.top]}else h=this.containment;b.pageX-this.offset.click.left<h[0]&&(f=h[0]+this.offset.click.left),b.pageY-this.offset.click.top<h[1]&&(g=h[1]+this.offset.click.top),b.pageX-this.offset.click.left>h[2]&&(f=h[2]+this.offset.click.left),b.pageY-this.offset.click.top>h[3]&&(g=h[3]+this.offset.click.top)}if(c.grid){var j=c.grid[1]?this.originalPageY+Math.round((g-this.originalPageY)/c.grid[1])*c.grid[1]:this.originalPageY;g=h?j-this.offset.click.top<h[1]||j-this.offset.click.top>h[3]?j-this.offset.click.top<h[1]?j+c.grid[1]:j-c.grid[1]:j:j;var k=c.grid[0]?this.originalPageX+Math.round((f-this.originalPageX)/c.grid[0])*c.grid[0]:this.originalPageX;f=h?k-this.offset.click.left<h[0]||k-this.offset.click.left>h[2]?k-this.offset.click.left<h[0]?k+c.grid[0]:k-c.grid[0]:k:k}}return{top:g-this.offset.click.top-this.offset.relative.top-this.offset.parent.top+(a.browser.safari&&a.browser.version<526&&this.cssPosition=="fixed"?0:this.cssPosition=="fixed"?-this.scrollParent.scrollTop():e?0:d.scrollTop()),left:f-this.offset.click.left-this.offset.relative.left-this.offset.parent.left+(a.browser.safari&&a.browser.version<526&&this.cssPosition=="fixed"?0:this.cssPosition=="fixed"?-this.scrollParent.scrollLeft():e?0:d.scrollLeft())}},_clear:function(){this.helper.removeClass("ui-draggable-dragging"),this.helper[0]!=this.element[0]&&!this.cancelHelperRemoval&&this.helper.remove(),this.helper=null,this.cancelHelperRemoval=!1},_trigger:function(b,c,d){return d=d||this._uiHash(),a.ui.plugin.call(this,b,[c,d]),b=="drag"&&(this.positionAbs=this._convertPositionTo("absolute")),a.Widget.prototype._trigger.call(this,b,c,d)},plugins:{},_uiHash:function(a){return{helper:this.helper,position:this.position,originalPosition:this.originalPosition,offset:this.positionAbs}}}),a.extend(a.ui.draggable,{version:"1.8.24"}),a.ui.plugin.add("draggable","connectToSortable",{start:function(b,c){var d=a(this).data("draggable"),e=d.options,f=a.extend({},c,{item:d.element});d.sortables=[],a(e.connectToSortable).each(function(){var c=a.data(this,"sortable");c&&!c.options.disabled&&(d.sortables.push({instance:c,shouldRevert:c.options.revert}),c.refreshPositions(),c._trigger("activate",b,f))})},stop:function(b,c){var d=a(this).data("draggable"),e=a.extend({},c,{item:d.element});a.each(d.sortables,function(){this.instance.isOver?(this.instance.isOver=0,d.cancelHelperRemoval=!0,this.instance.cancelHelperRemoval=!1,this.shouldRevert&&(this.instance.options.revert=!0),this.instance._mouseStop(b),this.instance.options.helper=this.instance.options._helper,d.options.helper=="original"&&this.instance.currentItem.css({top:"auto",left:"auto"})):(this.instance.cancelHelperRemoval=!1,this.instance._trigger("deactivate",b,e))})},drag:function(b,c){var d=a(this).data("draggable"),e=this,f=function(b){var c=this.offset.click.top,d=this.offset.click.left,e=this.positionAbs.top,f=this.positionAbs.left,g=b.height,h=b.width,i=b.top,j=b.left;return a.ui.isOver(e+c,f+d,i,j,g,h)};a.each(d.sortables,function(f){this.instance.positionAbs=d.positionAbs,this.instance.helperProportions=d.helperProportions,this.instance.offset.click=d.offset.click,this.instance._intersectsWith(this.instance.containerCache)?(this.instance.isOver||(this.instance.isOver=1,this.instance.currentItem=a(e).clone().removeAttr("id").appendTo(this.instance.element).data("sortable-item",!0),this.instance.options._helper=this.instance.options.helper,this.instance.options.helper=function(){return c.helper[0]},b.target=this.instance.currentItem[0],this.instance._mouseCapture(b,!0),this.instance._mouseStart(b,!0,!0),this.instance.offset.click.top=d.offset.click.top,this.instance.offset.click.left=d.offset.click.left,this.instance.offset.parent.left-=d.offset.parent.left-this.instance.offset.parent.left,this.instance.offset.parent.top-=d.offset.parent.top-this.instance.offset.parent.top,d._trigger("toSortable",b),d.dropped=this.instance.element,d.currentItem=d.element,this.instance.fromOutside=d),this.instance.currentItem&&this.instance._mouseDrag(b)):this.instance.isOver&&(this.instance.isOver=0,this.instance.cancelHelperRemoval=!0,this.instance.options.revert=!1,this.instance._trigger("out",b,this.instance._uiHash(this.instance)),this.instance._mouseStop(b,!0),this.instance.options.helper=this.instance.options._helper,this.instance.currentItem.remove(),this.instance.placeholder&&this.instance.placeholder.remove(),d._trigger("fromSortable",b),d.dropped=!1)})}}),a.ui.plugin.add("draggable","cursor",{start:function(b,c){var d=a("body"),e=a(this).data("draggable").options;d.css("cursor")&&(e._cursor=d.css("cursor")),d.css("cursor",e.cursor)},stop:function(b,c){var d=a(this).data("draggable").options;d._cursor&&a("body").css("cursor",d._cursor)}}),a.ui.plugin.add("draggable","opacity",{start:function(b,c){var d=a(c.helper),e=a(this).data("draggable").options;d.css("opacity")&&(e._opacity=d.css("opacity")),d.css("opacity",e.opacity)},stop:function(b,c){var d=a(this).data("draggable").options;d._opacity&&a(c.helper).css("opacity",d._opacity)}}),a.ui.plugin.add("draggable","scroll",{start:function(b,c){var d=a(this).data("draggable");d.scrollParent[0]!=document&&d.scrollParent[0].tagName!="HTML"&&(d.overflowOffset=d.scrollParent.offset())},drag:function(b,c){var d=a(this).data("draggable"),e=d.options,f=!1;if(d.scrollParent[0]!=document&&d.scrollParent[0].tagName!="HTML"){if(!e.axis||e.axis!="x")d.overflowOffset.top+d.scrollParent[0].offsetHeight-b.pageY<e.scrollSensitivity?d.scrollParent[0].scrollTop=f=d.scrollParent[0].scrollTop+e.scrollSpeed:b.pageY-d.overflowOffset.top<e.scrollSensitivity&&(d.scrollParent[0].scrollTop=f=d.scrollParent[0].scrollTop-e.scrollSpeed);if(!e.axis||e.axis!="y")d.overflowOffset.left+d.scrollParent[0].offsetWidth-b.pageX<e.scrollSensitivity?d.scrollParent[0].scrollLeft=f=d.scrollParent[0].scrollLeft+e.scrollSpeed:b.pageX-d.overflowOffset.left<e.scrollSensitivity&&(d.scrollParent[0].scrollLeft=f=d.scrollParent[0].scrollLeft-e.scrollSpeed)}else{if(!e.axis||e.axis!="x")b.pageY-a(document).scrollTop()<e.scrollSensitivity?f=a(document).scrollTop(a(document).scrollTop()-e.scrollSpeed):a(window).height()-(b.pageY-a(document).scrollTop())<e.scrollSensitivity&&(f=a(document).scrollTop(a(document).scrollTop()+e.scrollSpeed));if(!e.axis||e.axis!="y")b.pageX-a(document).scrollLeft()<e.scrollSensitivity?f=a(document).scrollLeft(a(document).scrollLeft()-e.scrollSpeed):a(window).width()-(b.pageX-a(document).scrollLeft())<e.scrollSensitivity&&(f=a(document).scrollLeft(a(document).scrollLeft()+e.scrollSpeed))}f!==!1&&a.ui.ddmanager&&!e.dropBehaviour&&a.ui.ddmanager.prepareOffsets(d,b)}}),a.ui.plugin.add("draggable","snap",{start:function(b,c){var d=a(this).data("draggable"),e=d.options;d.snapElements=[],a(e.snap.constructor!=String?e.snap.items||":data(draggable)":e.snap).each(function(){var b=a(this),c=b.offset();this!=d.element[0]&&d.snapElements.push({item:this,width:b.outerWidth(),height:b.outerHeight(),top:c.top,left:c.left})})},drag:function(b,c){var d=a(this).data("draggable"),e=d.options,f=e.snapTolerance,g=c.offset.left,h=g+d.helperProportions.width,i=c.offset.top,j=i+d.helperProportions.height;for(var k=d.snapElements.length-1;k>=0;k--){var l=d.snapElements[k].left,m=l+d.snapElements[k].width,n=d.snapElements[k].top,o=n+d.snapElements[k].height;if(!(l-f<g&&g<m+f&&n-f<i&&i<o+f||l-f<g&&g<m+f&&n-f<j&&j<o+f||l-f<h&&h<m+f&&n-f<i&&i<o+f||l-f<h&&h<m+f&&n-f<j&&j<o+f)){d.snapElements[k].snapping&&d.options.snap.release&&d.options.snap.release.call(d.element,b,a.extend(d._uiHash(),{snapItem:d.snapElements[k].item})),d.snapElements[k].snapping=!1;continue}if(e.snapMode!="inner"){var p=Math.abs(n-j)<=f,q=Math.abs(o-i)<=f,r=Math.abs(l-h)<=f,s=Math.abs(m-g)<=f;p&&(c.position.top=d._convertPositionTo("relative",{top:n-d.helperProportions.height,left:0}).top-d.margins.top),q&&(c.position.top=d._convertPositionTo("relative",{top:o,left:0}).top-d.margins.top),r&&(c.position.left=d._convertPositionTo("relative",{top:0,left:l-d.helperProportions.width}).left-d.margins.left),s&&(c.position.left=d._convertPositionTo("relative",{top:0,left:m}).left-d.margins.left)}var t=p||q||r||s;if(e.snapMode!="outer"){var p=Math.abs(n-i)<=f,q=Math.abs(o-j)<=f,r=Math.abs(l-g)<=f,s=Math.abs(m-h)<=f;p&&(c.position.top=d._convertPositionTo("relative",{top:n,left:0}).top-d.margins.top),q&&(c.position.top=d._convertPositionTo("relative",{top:o-d.helperProportions.height,left:0}).top-d.margins.top),r&&(c.position.left=d._convertPositionTo("relative",{top:0,left:l}).left-d.margins.left),s&&(c.position.left=d._convertPositionTo("relative",{top:0,left:m-d.helperProportions.width}).left-d.margins.left)}!d.snapElements[k].snapping&&(p||q||r||s||t)&&d.options.snap.snap&&d.options.snap.snap.call(d.element,b,a.extend(d._uiHash(),{snapItem:d.snapElements[k].item})),d.snapElements[k].snapping=p||q||r||s||t}}}),a.ui.plugin.add("draggable","stack",{start:function(b,c){var d=a(this).data("draggable").options,e=a.makeArray(a(d.stack)).sort(function(b,c){return(parseInt(a(b).css("zIndex"),10)||0)-(parseInt(a(c).css("zIndex"),10)||0)});if(!e.length)return;var f=parseInt(e[0].style.zIndex)||0;a(e).each(function(a){this.style.zIndex=f+a}),this[0].style.zIndex=f+e.length}}),a.ui.plugin.add("draggable","zIndex",{start:function(b,c){var d=a(c.helper),e=a(this).data("draggable").options;d.css("zIndex")&&(e._zIndex=d.css("zIndex")),d.css("zIndex",e.zIndex)},stop:function(b,c){var d=a(this).data("draggable").options;d._zIndex&&a(c.helper).css("zIndex",d._zIndex)}})})(jQuery);;/*! jQuery UI - v1.8.24 - 2012-09-28
* https://github.com/jquery/jquery-ui
* Includes: jquery.ui.droppable.js
* Copyright (c) 2012 AUTHORS.txt; Licensed MIT, GPL */
(function(a,b){a.widget("ui.droppable",{widgetEventPrefix:"drop",options:{accept:"*",activeClass:!1,addClasses:!0,greedy:!1,hoverClass:!1,scope:"default",tolerance:"intersect"},_create:function(){var b=this.options,c=b.accept;this.isover=0,this.isout=1,this.accept=a.isFunction(c)?c:function(a){return a.is(c)},this.proportions={width:this.element[0].offsetWidth,height:this.element[0].offsetHeight},a.ui.ddmanager.droppables[b.scope]=a.ui.ddmanager.droppables[b.scope]||[],a.ui.ddmanager.droppables[b.scope].push(this),b.addClasses&&this.element.addClass("ui-droppable")},destroy:function(){var b=a.ui.ddmanager.droppables[this.options.scope];for(var c=0;c<b.length;c++)b[c]==this&&b.splice(c,1);return this.element.removeClass("ui-droppable ui-droppable-disabled").removeData("droppable").unbind(".droppable"),this},_setOption:function(b,c){b=="accept"&&(this.accept=a.isFunction(c)?c:function(a){return a.is(c)}),a.Widget.prototype._setOption.apply(this,arguments)},_activate:function(b){var c=a.ui.ddmanager.current;this.options.activeClass&&this.element.addClass(this.options.activeClass),c&&this._trigger("activate",b,this.ui(c))},_deactivate:function(b){var c=a.ui.ddmanager.current;this.options.activeClass&&this.element.removeClass(this.options.activeClass),c&&this._trigger("deactivate",b,this.ui(c))},_over:function(b){var c=a.ui.ddmanager.current;if(!c||(c.currentItem||c.element)[0]==this.element[0])return;this.accept.call(this.element[0],c.currentItem||c.element)&&(this.options.hoverClass&&this.element.addClass(this.options.hoverClass),this._trigger("over",b,this.ui(c)))},_out:function(b){var c=a.ui.ddmanager.current;if(!c||(c.currentItem||c.element)[0]==this.element[0])return;this.accept.call(this.element[0],c.currentItem||c.element)&&(this.options.hoverClass&&this.element.removeClass(this.options.hoverClass),this._trigger("out",b,this.ui(c)))},_drop:function(b,c){var d=c||a.ui.ddmanager.current;if(!d||(d.currentItem||d.element)[0]==this.element[0])return!1;var e=!1;return this.element.find(":data(droppable)").not(".ui-draggable-dragging").each(function(){var b=a.data(this,"droppable");if(b.options.greedy&&!b.options.disabled&&b.options.scope==d.options.scope&&b.accept.call(b.element[0],d.currentItem||d.element)&&a.ui.intersect(d,a.extend(b,{offset:b.element.offset()}),b.options.tolerance))return e=!0,!1}),e?!1:this.accept.call(this.element[0],d.currentItem||d.element)?(this.options.activeClass&&this.element.removeClass(this.options.activeClass),this.options.hoverClass&&this.element.removeClass(this.options.hoverClass),this._trigger("drop",b,this.ui(d)),this.element):!1},ui:function(a){return{draggable:a.currentItem||a.element,helper:a.helper,position:a.position,offset:a.positionAbs}}}),a.extend(a.ui.droppable,{version:"1.8.24"}),a.ui.intersect=function(b,c,d){if(!c.offset)return!1;var e=(b.positionAbs||b.position.absolute).left,f=e+b.helperProportions.width,g=(b.positionAbs||b.position.absolute).top,h=g+b.helperProportions.height,i=c.offset.left,j=i+c.proportions.width,k=c.offset.top,l=k+c.proportions.height;switch(d){case"fit":return i<=e&&f<=j&&k<=g&&h<=l;case"intersect":return i<e+b.helperProportions.width/2&&f-b.helperProportions.width/2<j&&k<g+b.helperProportions.height/2&&h-b.helperProportions.height/2<l;case"pointer":var m=(b.positionAbs||b.position.absolute).left+(b.clickOffset||b.offset.click).left,n=(b.positionAbs||b.position.absolute).top+(b.clickOffset||b.offset.click).top,o=a.ui.isOver(n,m,k,i,c.proportions.height,c.proportions.width);return o;case"touch":return(g>=k&&g<=l||h>=k&&h<=l||g<k&&h>l)&&(e>=i&&e<=j||f>=i&&f<=j||e<i&&f>j);default:return!1}},a.ui.ddmanager={current:null,droppables:{"default":[]},prepareOffsets:function(b,c){var d=a.ui.ddmanager.droppables[b.options.scope]||[],e=c?c.type:null,f=(b.currentItem||b.element).find(":data(droppable)").andSelf();g:for(var h=0;h<d.length;h++){if(d[h].options.disabled||b&&!d[h].accept.call(d[h].element[0],b.currentItem||b.element))continue;for(var i=0;i<f.length;i++)if(f[i]==d[h].element[0]){d[h].proportions.height=0;continue g}d[h].visible=d[h].element.css("display")!="none";if(!d[h].visible)continue;e=="mousedown"&&d[h]._activate.call(d[h],c),d[h].offset=d[h].element.offset(),d[h].proportions={width:d[h].element[0].offsetWidth,height:d[h].element[0].offsetHeight}}},drop:function(b,c){var d=!1;return a.each(a.ui.ddmanager.droppables[b.options.scope]||[],function(){if(!this.options)return;!this.options.disabled&&this.visible&&a.ui.intersect(b,this,this.options.tolerance)&&(d=this._drop.call(this,c)||d),!this.options.disabled&&this.visible&&this.accept.call(this.element[0],b.currentItem||b.element)&&(this.isout=1,this.isover=0,this._deactivate.call(this,c))}),d},dragStart:function(b,c){b.element.parents(":not(body,html)").bind("scroll.droppable",function(){b.options.refreshPositions||a.ui.ddmanager.prepareOffsets(b,c)})},drag:function(b,c){b.options.refreshPositions&&a.ui.ddmanager.prepareOffsets(b,c),a.each(a.ui.ddmanager.droppables[b.options.scope]||[],function(){if(this.options.disabled||this.greedyChild||!this.visible)return;var d=a.ui.intersect(b,this,this.options.tolerance),e=!d&&this.isover==1?"isout":d&&this.isover==0?"isover":null;if(!e)return;var f;if(this.options.greedy){var g=this.options.scope,h=this.element.parents(":data(droppable)").filter(function(){return a.data(this,"droppable").options.scope===g});h.length&&(f=a.data(h[0],"droppable"),f.greedyChild=e=="isover"?1:0)}f&&e=="isover"&&(f.isover=0,f.isout=1,f._out.call(f,c)),this[e]=1,this[e=="isout"?"isover":"isout"]=0,this[e=="isover"?"_over":"_out"].call(this,c),f&&e=="isout"&&(f.isout=0,f.isover=1,f._over.call(f,c))})},dragStop:function(b,c){b.element.parents(":not(body,html)").unbind("scroll.droppable"),b.options.refreshPositions||a.ui.ddmanager.prepareOffsets(b,c)}}})(jQuery);;/*! jQuery UI - v1.8.24 - 2012-09-28
* https://github.com/jquery/jquery-ui
* Includes: jquery.ui.resizable.js
* Copyright (c) 2012 AUTHORS.txt; Licensed MIT, GPL */
(function(a,b){a.widget("ui.resizable",a.ui.mouse,{widgetEventPrefix:"resize",options:{alsoResize:!1,animate:!1,animateDuration:"slow",animateEasing:"swing",aspectRatio:!1,autoHide:!1,containment:!1,ghost:!1,grid:!1,handles:"e,s,se",helper:!1,maxHeight:null,maxWidth:null,minHeight:10,minWidth:10,zIndex:1e3},_create:function(){var b=this,c=this.options;this.element.addClass("ui-resizable"),a.extend(this,{_aspectRatio:!!c.aspectRatio,aspectRatio:c.aspectRatio,originalElement:this.element,_proportionallyResizeElements:[],_helper:c.helper||c.ghost||c.animate?c.helper||"ui-resizable-helper":null}),this.element[0].nodeName.match(/canvas|textarea|input|select|button|img/i)&&(this.element.wrap(a('<div class="ui-wrapper" style="overflow: hidden;"></div>').css({position:this.element.css("position"),width:this.element.outerWidth(),height:this.element.outerHeight(),top:this.element.css("top"),left:this.element.css("left")})),this.element=this.element.parent().data("resizable",this.element.data("resizable")),this.elementIsWrapper=!0,this.element.css({marginLeft:this.originalElement.css("marginLeft"),marginTop:this.originalElement.css("marginTop"),marginRight:this.originalElement.css("marginRight"),marginBottom:this.originalElement.css("marginBottom")}),this.originalElement.css({marginLeft:0,marginTop:0,marginRight:0,marginBottom:0}),this.originalResizeStyle=this.originalElement.css("resize"),this.originalElement.css("resize","none"),this._proportionallyResizeElements.push(this.originalElement.css({position:"static",zoom:1,display:"block"})),this.originalElement.css({margin:this.originalElement.css("margin")}),this._proportionallyResize()),this.handles=c.handles||(a(".ui-resizable-handle",this.element).length?{n:".ui-resizable-n",e:".ui-resizable-e",s:".ui-resizable-s",w:".ui-resizable-w",se:".ui-resizable-se",sw:".ui-resizable-sw",ne:".ui-resizable-ne",nw:".ui-resizable-nw"}:"e,s,se");if(this.handles.constructor==String){this.handles=="all"&&(this.handles="n,e,s,w,se,sw,ne,nw");var d=this.handles.split(",");this.handles={};for(var e=0;e<d.length;e++){var f=a.trim(d[e]),g="ui-resizable-"+f,h=a('<div class="ui-resizable-handle '+g+'"></div>');h.css({zIndex:c.zIndex}),"se"==f&&h.addClass("ui-icon ui-icon-gripsmall-diagonal-se"),this.handles[f]=".ui-resizable-"+f,this.element.append(h)}}this._renderAxis=function(b){b=b||this.element;for(var c in this.handles){this.handles[c].constructor==String&&(this.handles[c]=a(this.handles[c],this.element).show());if(this.elementIsWrapper&&this.originalElement[0].nodeName.match(/textarea|input|select|button/i)){var d=a(this.handles[c],this.element),e=0;e=/sw|ne|nw|se|n|s/.test(c)?d.outerHeight():d.outerWidth();var f=["padding",/ne|nw|n/.test(c)?"Top":/se|sw|s/.test(c)?"Bottom":/^e$/.test(c)?"Right":"Left"].join("");b.css(f,e),this._proportionallyResize()}if(!a(this.handles[c]).length)continue}},this._renderAxis(this.element),this._handles=a(".ui-resizable-handle",this.element).disableSelection(),this._handles.mouseover(function(){if(!b.resizing){if(this.className)var a=this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i);b.axis=a&&a[1]?a[1]:"se"}}),c.autoHide&&(this._handles.hide(),a(this.element).addClass("ui-resizable-autohide").hover(function(){if(c.disabled)return;a(this).removeClass("ui-resizable-autohide"),b._handles.show()},function(){if(c.disabled)return;b.resizing||(a(this).addClass("ui-resizable-autohide"),b._handles.hide())})),this._mouseInit()},destroy:function(){this._mouseDestroy();var b=function(b){a(b).removeClass("ui-resizable ui-resizable-disabled ui-resizable-resizing").removeData("resizable").unbind(".resizable").find(".ui-resizable-handle").remove()};if(this.elementIsWrapper){b(this.element);var c=this.element;c.after(this.originalElement.css({position:c.css("position"),width:c.outerWidth(),height:c.outerHeight(),top:c.css("top"),left:c.css("left")})).remove()}return this.originalElement.css("resize",this.originalResizeStyle),b(this.originalElement),this},_mouseCapture:function(b){var c=!1;for(var d in this.handles)a(this.handles[d])[0]==b.target&&(c=!0);return!this.options.disabled&&c},_mouseStart:function(b){var d=this.options,e=this.element.position(),f=this.element;this.resizing=!0,this.documentScroll={top:a(document).scrollTop(),left:a(document).scrollLeft()},(f.is(".ui-draggable")||/absolute/.test(f.css("position")))&&f.css({position:"absolute",top:e.top,left:e.left}),this._renderProxy();var g=c(this.helper.css("left")),h=c(this.helper.css("top"));d.containment&&(g+=a(d.containment).scrollLeft()||0,h+=a(d.containment).scrollTop()||0),this.offset=this.helper.offset(),this.position={left:g,top:h},this.size=this._helper?{width:f.outerWidth(),height:f.outerHeight()}:{width:f.width(),height:f.height()},this.originalSize=this._helper?{width:f.outerWidth(),height:f.outerHeight()}:{width:f.width(),height:f.height()},this.originalPosition={left:g,top:h},this.sizeDiff={width:f.outerWidth()-f.width(),height:f.outerHeight()-f.height()},this.originalMousePosition={left:b.pageX,top:b.pageY},this.aspectRatio=typeof d.aspectRatio=="number"?d.aspectRatio:this.originalSize.width/this.originalSize.height||1;var i=a(".ui-resizable-"+this.axis).css("cursor");return a("body").css("cursor",i=="auto"?this.axis+"-resize":i),f.addClass("ui-resizable-resizing"),this._propagate("start",b),!0},_mouseDrag:function(b){var c=this.helper,d=this.options,e={},f=this,g=this.originalMousePosition,h=this.axis,i=b.pageX-g.left||0,j=b.pageY-g.top||0,k=this._change[h];if(!k)return!1;var l=k.apply(this,[b,i,j]),m=a.browser.msie&&a.browser.version<7,n=this.sizeDiff;this._updateVirtualBoundaries(b.shiftKey);if(this._aspectRatio||b.shiftKey)l=this._updateRatio(l,b);return l=this._respectSize(l,b),this._propagate("resize",b),c.css({top:this.position.top+"px",left:this.position.left+"px",width:this.size.width+"px",height:this.size.height+"px"}),!this._helper&&this._proportionallyResizeElements.length&&this._proportionallyResize(),this._updateCache(l),this._trigger("resize",b,this.ui()),!1},_mouseStop:function(b){this.resizing=!1;var c=this.options,d=this;if(this._helper){var e=this._proportionallyResizeElements,f=e.length&&/textarea/i.test(e[0].nodeName),g=f&&a.ui.hasScroll(e[0],"left")?0:d.sizeDiff.height,h=f?0:d.sizeDiff.width,i={width:d.helper.width()-h,height:d.helper.height()-g},j=parseInt(d.element.css("left"),10)+(d.position.left-d.originalPosition.left)||null,k=parseInt(d.element.css("top"),10)+(d.position.top-d.originalPosition.top)||null;c.animate||this.element.css(a.extend(i,{top:k,left:j})),d.helper.height(d.size.height),d.helper.width(d.size.width),this._helper&&!c.animate&&this._proportionallyResize()}return a("body").css("cursor","auto"),this.element.removeClass("ui-resizable-resizing"),this._propagate("stop",b),this._helper&&this.helper.remove(),!1},_updateVirtualBoundaries:function(a){var b=this.options,c,e,f,g,h;h={minWidth:d(b.minWidth)?b.minWidth:0,maxWidth:d(b.maxWidth)?b.maxWidth:Infinity,minHeight:d(b.minHeight)?b.minHeight:0,maxHeight:d(b.maxHeight)?b.maxHeight:Infinity};if(this._aspectRatio||a)c=h.minHeight*this.aspectRatio,f=h.minWidth/this.aspectRatio,e=h.maxHeight*this.aspectRatio,g=h.maxWidth/this.aspectRatio,c>h.minWidth&&(h.minWidth=c),f>h.minHeight&&(h.minHeight=f),e<h.maxWidth&&(h.maxWidth=e),g<h.maxHeight&&(h.maxHeight=g);this._vBoundaries=h},_updateCache:function(a){var b=this.options;this.offset=this.helper.offset(),d(a.left)&&(this.position.left=a.left),d(a.top)&&(this.position.top=a.top),d(a.height)&&(this.size.height=a.height),d(a.width)&&(this.size.width=a.width)},_updateRatio:function(a,b){var c=this.options,e=this.position,f=this.size,g=this.axis;return d(a.height)?a.width=a.height*this.aspectRatio:d(a.width)&&(a.height=a.width/this.aspectRatio),g=="sw"&&(a.left=e.left+(f.width-a.width),a.top=null),g=="nw"&&(a.top=e.top+(f.height-a.height),a.left=e.left+(f.width-a.width)),a},_respectSize:function(a,b){var c=this.helper,e=this._vBoundaries,f=this._aspectRatio||b.shiftKey,g=this.axis,h=d(a.width)&&e.maxWidth&&e.maxWidth<a.width,i=d(a.height)&&e.maxHeight&&e.maxHeight<a.height,j=d(a.width)&&e.minWidth&&e.minWidth>a.width,k=d(a.height)&&e.minHeight&&e.minHeight>a.height;j&&(a.width=e.minWidth),k&&(a.height=e.minHeight),h&&(a.width=e.maxWidth),i&&(a.height=e.maxHeight);var l=this.originalPosition.left+this.originalSize.width,m=this.position.top+this.size.height,n=/sw|nw|w/.test(g),o=/nw|ne|n/.test(g);j&&n&&(a.left=l-e.minWidth),h&&n&&(a.left=l-e.maxWidth),k&&o&&(a.top=m-e.minHeight),i&&o&&(a.top=m-e.maxHeight);var p=!a.width&&!a.height;return p&&!a.left&&a.top?a.top=null:p&&!a.top&&a.left&&(a.left=null),a},_proportionallyResize:function(){var b=this.options;if(!this._proportionallyResizeElements.length)return;var c=this.helper||this.element;for(var d=0;d<this._proportionallyResizeElements.length;d++){var e=this._proportionallyResizeElements[d];if(!this.borderDif){var f=[e.css("borderTopWidth"),e.css("borderRightWidth"),e.css("borderBottomWidth"),e.css("borderLeftWidth")],g=[e.css("paddingTop"),e.css("paddingRight"),e.css("paddingBottom"),e.css("paddingLeft")];this.borderDif=a.map(f,function(a,b){var c=parseInt(a,10)||0,d=parseInt(g[b],10)||0;return c+d})}if(!a.browser.msie||!a(c).is(":hidden")&&!a(c).parents(":hidden").length)e.css({height:c.height()-this.borderDif[0]-this.borderDif[2]||0,width:c.width()-this.borderDif[1]-this.borderDif[3]||0});else continue}},_renderProxy:function(){var b=this.element,c=this.options;this.elementOffset=b.offset();if(this._helper){this.helper=this.helper||a('<div style="overflow:hidden;"></div>');var d=a.browser.msie&&a.browser.version<7,e=d?1:0,f=d?2:-1;this.helper.addClass(this._helper).css({width:this.element.outerWidth()+f,height:this.element.outerHeight()+f,position:"absolute",left:this.elementOffset.left-e+"px",top:this.elementOffset.top-e+"px",zIndex:++c.zIndex}),this.helper.appendTo("body").disableSelection()}else this.helper=this.element},_change:{e:function(a,b,c){return{width:this.originalSize.width+b}},w:function(a,b,c){var d=this.options,e=this.originalSize,f=this.originalPosition;return{left:f.left+b,width:e.width-b}},n:function(a,b,c){var d=this.options,e=this.originalSize,f=this.originalPosition;return{top:f.top+c,height:e.height-c}},s:function(a,b,c){return{height:this.originalSize.height+c}},se:function(b,c,d){return a.extend(this._change.s.apply(this,arguments),this._change.e.apply(this,[b,c,d]))},sw:function(b,c,d){return a.extend(this._change.s.apply(this,arguments),this._change.w.apply(this,[b,c,d]))},ne:function(b,c,d){return a.extend(this._change.n.apply(this,arguments),this._change.e.apply(this,[b,c,d]))},nw:function(b,c,d){return a.extend(this._change.n.apply(this,arguments),this._change.w.apply(this,[b,c,d]))}},_propagate:function(b,c){a.ui.plugin.call(this,b,[c,this.ui()]),b!="resize"&&this._trigger(b,c,this.ui())},plugins:{},ui:function(){return{originalElement:this.originalElement,element:this.element,helper:this.helper,position:this.position,size:this.size,originalSize:this.originalSize,originalPosition:this.originalPosition}}}),a.extend(a.ui.resizable,{version:"1.8.24"}),a.ui.plugin.add("resizable","alsoResize",{start:function(b,c){var d=a(this).data("resizable"),e=d.options,f=function(b){a(b).each(function(){var b=a(this);b.data("resizable-alsoresize",{width:parseInt(b.width(),10),height:parseInt(b.height(),10),left:parseInt(b.css("left"),10),top:parseInt(b.css("top"),10)})})};typeof e.alsoResize=="object"&&!e.alsoResize.parentNode?e.alsoResize.length?(e.alsoResize=e.alsoResize[0],f(e.alsoResize)):a.each(e.alsoResize,function(a){f(a)}):f(e.alsoResize)},resize:function(b,c){var d=a(this).data("resizable"),e=d.options,f=d.originalSize,g=d.originalPosition,h={height:d.size.height-f.height||0,width:d.size.width-f.width||0,top:d.position.top-g.top||0,left:d.position.left-g.left||0},i=function(b,d){a(b).each(function(){var b=a(this),e=a(this).data("resizable-alsoresize"),f={},g=d&&d.length?d:b.parents(c.originalElement[0]).length?["width","height"]:["width","height","top","left"];a.each(g,function(a,b){var c=(e[b]||0)+(h[b]||0);c&&c>=0&&(f[b]=c||null)}),b.css(f)})};typeof e.alsoResize=="object"&&!e.alsoResize.nodeType?a.each(e.alsoResize,function(a,b){i(a,b)}):i(e.alsoResize)},stop:function(b,c){a(this).removeData("resizable-alsoresize")}}),a.ui.plugin.add("resizable","animate",{stop:function(b,c){var d=a(this).data("resizable"),e=d.options,f=d._proportionallyResizeElements,g=f.length&&/textarea/i.test(f[0].nodeName),h=g&&a.ui.hasScroll(f[0],"left")?0:d.sizeDiff.height,i=g?0:d.sizeDiff.width,j={width:d.size.width-i,height:d.size.height-h},k=parseInt(d.element.css("left"),10)+(d.position.left-d.originalPosition.left)||null,l=parseInt(d.element.css("top"),10)+(d.position.top-d.originalPosition.top)||null;d.element.animate(a.extend(j,l&&k?{top:l,left:k}:{}),{duration:e.animateDuration,easing:e.animateEasing,step:function(){var c={width:parseInt(d.element.css("width"),10),height:parseInt(d.element.css("height"),10),top:parseInt(d.element.css("top"),10),left:parseInt(d.element.css("left"),10)};f&&f.length&&a(f[0]).css({width:c.width,height:c.height}),d._updateCache(c),d._propagate("resize",b)}})}}),a.ui.plugin.add("resizable","containment",{start:function(b,d){var e=a(this).data("resizable"),f=e.options,g=e.element,h=f.containment,i=h instanceof a?h.get(0):/parent/.test(h)?g.parent().get(0):h;if(!i)return;e.containerElement=a(i);if(/document/.test(h)||h==document)e.containerOffset={left:0,top:0},e.containerPosition={left:0,top:0},e.parentData={element:a(document),left:0,top:0,width:a(document).width(),height:a(document).height()||document.body.parentNode.scrollHeight};else{var j=a(i),k=[];a(["Top","Right","Left","Bottom"]).each(function(a,b){k[a]=c(j.css("padding"+b))}),e.containerOffset=j.offset(),e.containerPosition=j.position(),e.containerSize={height:j.innerHeight()-k[3],width:j.innerWidth()-k[1]};var l=e.containerOffset,m=e.containerSize.height,n=e.containerSize.width,o=a.ui.hasScroll(i,"left")?i.scrollWidth:n,p=a.ui.hasScroll(i)?i.scrollHeight:m;e.parentData={element:i,left:l.left,top:l.top,width:o,height:p}}},resize:function(b,c){var d=a(this).data("resizable"),e=d.options,f=d.containerSize,g=d.containerOffset,h=d.size,i=d.position,j=d._aspectRatio||b.shiftKey,k={top:0,left:0},l=d.containerElement;l[0]!=document&&/static/.test(l.css("position"))&&(k=g),i.left<(d._helper?g.left:0)&&(d.size.width=d.size.width+(d._helper?d.position.left-g.left:d.position.left-k.left),j&&(d.size.height=d.size.width/d.aspectRatio),d.position.left=e.helper?g.left:0),i.top<(d._helper?g.top:0)&&(d.size.height=d.size.height+(d._helper?d.position.top-g.top:d.position.top),j&&(d.size.width=d.size.height*d.aspectRatio),d.position.top=d._helper?g.top:0),d.offset.left=d.parentData.left+d.position.left,d.offset.top=d.parentData.top+d.position.top;var m=Math.abs((d._helper?d.offset.left-k.left:d.offset.left-k.left)+d.sizeDiff.width),n=Math.abs((d._helper?d.offset.top-k.top:d.offset.top-g.top)+d.sizeDiff.height),o=d.containerElement.get(0)==d.element.parent().get(0),p=/relative|absolute/.test(d.containerElement.css("position"));o&&p&&(m-=d.parentData.left),m+d.size.width>=d.parentData.width&&(d.size.width=d.parentData.width-m,j&&(d.size.height=d.size.width/d.aspectRatio)),n+d.size.height>=d.parentData.height&&(d.size.height=d.parentData.height-n,j&&(d.size.width=d.size.height*d.aspectRatio))},stop:function(b,c){var d=a(this).data("resizable"),e=d.options,f=d.position,g=d.containerOffset,h=d.containerPosition,i=d.containerElement,j=a(d.helper),k=j.offset(),l=j.outerWidth()-d.sizeDiff.width,m=j.outerHeight()-d.sizeDiff.height;d._helper&&!e.animate&&/relative/.test(i.css("position"))&&a(this).css({left:k.left-h.left-g.left,width:l,height:m}),d._helper&&!e.animate&&/static/.test(i.css("position"))&&a(this).css({left:k.left-h.left-g.left,width:l,height:m})}}),a.ui.plugin.add("resizable","ghost",{start:function(b,c){var d=a(this).data("resizable"),e=d.options,f=d.size;d.ghost=d.originalElement.clone(),d.ghost.css({opacity:.25,display:"block",position:"relative",height:f.height,width:f.width,margin:0,left:0,top:0}).addClass("ui-resizable-ghost").addClass(typeof e.ghost=="string"?e.ghost:""),d.ghost.appendTo(d.helper)},resize:function(b,c){var d=a(this).data("resizable"),e=d.options;d.ghost&&d.ghost.css({position:"relative",height:d.size.height,width:d.size.width})},stop:function(b,c){var d=a(this).data("resizable"),e=d.options;d.ghost&&d.helper&&d.helper.get(0).removeChild(d.ghost.get(0))}}),a.ui.plugin.add("resizable","grid",{resize:function(b,c){var d=a(this).data("resizable"),e=d.options,f=d.size,g=d.originalSize,h=d.originalPosition,i=d.axis,j=e._aspectRatio||b.shiftKey;e.grid=typeof e.grid=="number"?[e.grid,e.grid]:e.grid;var k=Math.round((f.width-g.width)/(e.grid[0]||1))*(e.grid[0]||1),l=Math.round((f.height-g.height)/(e.grid[1]||1))*(e.grid[1]||1);/^(se|s|e)$/.test(i)?(d.size.width=g.width+k,d.size.height=g.height+l):/^(ne)$/.test(i)?(d.size.width=g.width+k,d.size.height=g.height+l,d.position.top=h.top-l):/^(sw)$/.test(i)?(d.size.width=g.width+k,d.size.height=g.height+l,d.position.left=h.left-k):(d.size.width=g.width+k,d.size.height=g.height+l,d.position.top=h.top-l,d.position.left=h.left-k)}});var c=function(a){return parseInt(a,10)||0},d=function(a){return!isNaN(parseInt(a,10))}})(jQuery);;/*! jQuery UI - v1.8.24 - 2012-09-28
* https://github.com/jquery/jquery-ui
* Includes: jquery.ui.selectable.js
* Copyright (c) 2012 AUTHORS.txt; Licensed MIT, GPL */
(function(a,b){a.widget("ui.selectable",a.ui.mouse,{options:{appendTo:"body",autoRefresh:!0,distance:0,filter:"*",tolerance:"touch"},_create:function(){var b=this;this.element.addClass("ui-selectable"),this.dragged=!1;var c;this.refresh=function(){c=a(b.options.filter,b.element[0]),c.addClass("ui-selectee"),c.each(function(){var b=a(this),c=b.offset();a.data(this,"selectable-item",{element:this,$element:b,left:c.left,top:c.top,right:c.left+b.outerWidth(),bottom:c.top+b.outerHeight(),startselected:!1,selected:b.hasClass("ui-selected"),selecting:b.hasClass("ui-selecting"),unselecting:b.hasClass("ui-unselecting")})})},this.refresh(),this.selectees=c.addClass("ui-selectee"),this._mouseInit(),this.helper=a("<div class='ui-selectable-helper'></div>")},destroy:function(){return this.selectees.removeClass("ui-selectee").removeData("selectable-item"),this.element.removeClass("ui-selectable ui-selectable-disabled").removeData("selectable").unbind(".selectable"),this._mouseDestroy(),this},_mouseStart:function(b){var c=this;this.opos=[b.pageX,b.pageY];if(this.options.disabled)return;var d=this.options;this.selectees=a(d.filter,this.element[0]),this._trigger("start",b),a(d.appendTo).append(this.helper),this.helper.css({left:b.clientX,top:b.clientY,width:0,height:0}),d.autoRefresh&&this.refresh(),this.selectees.filter(".ui-selected").each(function(){var d=a.data(this,"selectable-item");d.startselected=!0,!b.metaKey&&!b.ctrlKey&&(d.$element.removeClass("ui-selected"),d.selected=!1,d.$element.addClass("ui-unselecting"),d.unselecting=!0,c._trigger("unselecting",b,{unselecting:d.element}))}),a(b.target).parents().andSelf().each(function(){var d=a.data(this,"selectable-item");if(d){var e=!b.metaKey&&!b.ctrlKey||!d.$element.hasClass("ui-selected");return d.$element.removeClass(e?"ui-unselecting":"ui-selected").addClass(e?"ui-selecting":"ui-unselecting"),d.unselecting=!e,d.selecting=e,d.selected=e,e?c._trigger("selecting",b,{selecting:d.element}):c._trigger("unselecting",b,{unselecting:d.element}),!1}})},_mouseDrag:function(b){var c=this;this.dragged=!0;if(this.options.disabled)return;var d=this.options,e=this.opos[0],f=this.opos[1],g=b.pageX,h=b.pageY;if(e>g){var i=g;g=e,e=i}if(f>h){var i=h;h=f,f=i}return this.helper.css({left:e,top:f,width:g-e,height:h-f}),this.selectees.each(function(){var i=a.data(this,"selectable-item");if(!i||i.element==c.element[0])return;var j=!1;d.tolerance=="touch"?j=!(i.left>g||i.right<e||i.top>h||i.bottom<f):d.tolerance=="fit"&&(j=i.left>e&&i.right<g&&i.top>f&&i.bottom<h),j?(i.selected&&(i.$element.removeClass("ui-selected"),i.selected=!1),i.unselecting&&(i.$element.removeClass("ui-unselecting"),i.unselecting=!1),i.selecting||(i.$element.addClass("ui-selecting"),i.selecting=!0,c._trigger("selecting",b,{selecting:i.element}))):(i.selecting&&((b.metaKey||b.ctrlKey)&&i.startselected?(i.$element.removeClass("ui-selecting"),i.selecting=!1,i.$element.addClass("ui-selected"),i.selected=!0):(i.$element.removeClass("ui-selecting"),i.selecting=!1,i.startselected&&(i.$element.addClass("ui-unselecting"),i.unselecting=!0),c._trigger("unselecting",b,{unselecting:i.element}))),i.selected&&!b.metaKey&&!b.ctrlKey&&!i.startselected&&(i.$element.removeClass("ui-selected"),i.selected=!1,i.$element.addClass("ui-unselecting"),i.unselecting=!0,c._trigger("unselecting",b,{unselecting:i.element})))}),!1},_mouseStop:function(b){var c=this;this.dragged=!1;var d=this.options;return a(".ui-unselecting",this.element[0]).each(function(){var d=a.data(this,"selectable-item");d.$element.removeClass("ui-unselecting"),d.unselecting=!1,d.startselected=!1,c._trigger("unselected",b,{unselected:d.element})}),a(".ui-selecting",this.element[0]).each(function(){var d=a.data(this,"selectable-item");d.$element.removeClass("ui-selecting").addClass("ui-selected"),d.selecting=!1,d.selected=!0,d.startselected=!0,c._trigger("selected",b,{selected:d.element})}),this._trigger("stop",b),this.helper.remove(),!1}}),a.extend(a.ui.selectable,{version:"1.8.24"})})(jQuery);;/*! jQuery UI - v1.8.24 - 2012-09-28
* https://github.com/jquery/jquery-ui
* Includes: jquery.ui.sortable.js
* Copyright (c) 2012 AUTHORS.txt; Licensed MIT, GPL */
(function(a,b){a.widget("ui.sortable",a.ui.mouse,{widgetEventPrefix:"sort",ready:!1,options:{appendTo:"parent",axis:!1,connectWith:!1,containment:!1,cursor:"auto",cursorAt:!1,dropOnEmpty:!0,forcePlaceholderSize:!1,forceHelperSize:!1,grid:!1,handle:!1,helper:"original",items:"> *",opacity:!1,placeholder:!1,revert:!1,scroll:!0,scrollSensitivity:20,scrollSpeed:20,scope:"default",tolerance:"intersect",zIndex:1e3},_create:function(){var a=this.options;this.containerCache={},this.element.addClass("ui-sortable"),this.refresh(),this.floating=this.items.length?a.axis==="x"||/left|right/.test(this.items[0].item.css("float"))||/inline|table-cell/.test(this.items[0].item.css("display")):!1,this.offset=this.element.offset(),this._mouseInit(),this.ready=!0},destroy:function(){a.Widget.prototype.destroy.call(this),this.element.removeClass("ui-sortable ui-sortable-disabled"),this._mouseDestroy();for(var b=this.items.length-1;b>=0;b--)this.items[b].item.removeData(this.widgetName+"-item");return this},_setOption:function(b,c){b==="disabled"?(this.options[b]=c,this.widget()[c?"addClass":"removeClass"]("ui-sortable-disabled")):a.Widget.prototype._setOption.apply(this,arguments)},_mouseCapture:function(b,c){var d=this;if(this.reverting)return!1;if(this.options.disabled||this.options.type=="static")return!1;this._refreshItems(b);var e=null,f=this,g=a(b.target).parents().each(function(){if(a.data(this,d.widgetName+"-item")==f)return e=a(this),!1});a.data(b.target,d.widgetName+"-item")==f&&(e=a(b.target));if(!e)return!1;if(this.options.handle&&!c){var h=!1;a(this.options.handle,e).find("*").andSelf().each(function(){this==b.target&&(h=!0)});if(!h)return!1}return this.currentItem=e,this._removeCurrentsFromItems(),!0},_mouseStart:function(b,c,d){var e=this.options,f=this;this.currentContainer=this,this.refreshPositions(),this.helper=this._createHelper(b),this._cacheHelperProportions(),this._cacheMargins(),this.scrollParent=this.helper.scrollParent(),this.offset=this.currentItem.offset(),this.offset={top:this.offset.top-this.margins.top,left:this.offset.left-this.margins.left},a.extend(this.offset,{click:{left:b.pageX-this.offset.left,top:b.pageY-this.offset.top},parent:this._getParentOffset(),relative:this._getRelativeOffset()}),this.helper.css("position","absolute"),this.cssPosition=this.helper.css("position"),this.originalPosition=this._generatePosition(b),this.originalPageX=b.pageX,this.originalPageY=b.pageY,e.cursorAt&&this._adjustOffsetFromHelper(e.cursorAt),this.domPosition={prev:this.currentItem.prev()[0],parent:this.currentItem.parent()[0]},this.helper[0]!=this.currentItem[0]&&this.currentItem.hide(),this._createPlaceholder(),e.containment&&this._setContainment(),e.cursor&&(a("body").css("cursor")&&(this._storedCursor=a("body").css("cursor")),a("body").css("cursor",e.cursor)),e.opacity&&(this.helper.css("opacity")&&(this._storedOpacity=this.helper.css("opacity")),this.helper.css("opacity",e.opacity)),e.zIndex&&(this.helper.css("zIndex")&&(this._storedZIndex=this.helper.css("zIndex")),this.helper.css("zIndex",e.zIndex)),this.scrollParent[0]!=document&&this.scrollParent[0].tagName!="HTML"&&(this.overflowOffset=this.scrollParent.offset()),this._trigger("start",b,this._uiHash()),this._preserveHelperProportions||this._cacheHelperProportions();if(!d)for(var g=this.containers.length-1;g>=0;g--)this.containers[g]._trigger("activate",b,f._uiHash(this));return a.ui.ddmanager&&(a.ui.ddmanager.current=this),a.ui.ddmanager&&!e.dropBehaviour&&a.ui.ddmanager.prepareOffsets(this,b),this.dragging=!0,this.helper.addClass("ui-sortable-helper"),this._mouseDrag(b),!0},_mouseDrag:function(b){this.position=this._generatePosition(b),this.positionAbs=this._convertPositionTo("absolute"),this.lastPositionAbs||(this.lastPositionAbs=this.positionAbs);if(this.options.scroll){var c=this.options,d=!1;this.scrollParent[0]!=document&&this.scrollParent[0].tagName!="HTML"?(this.overflowOffset.top+this.scrollParent[0].offsetHeight-b.pageY<c.scrollSensitivity?this.scrollParent[0].scrollTop=d=this.scrollParent[0].scrollTop+c.scrollSpeed:b.pageY-this.overflowOffset.top<c.scrollSensitivity&&(this.scrollParent[0].scrollTop=d=this.scrollParent[0].scrollTop-c.scrollSpeed),this.overflowOffset.left+this.scrollParent[0].offsetWidth-b.pageX<c.scrollSensitivity?this.scrollParent[0].scrollLeft=d=this.scrollParent[0].scrollLeft+c.scrollSpeed:b.pageX-this.overflowOffset.left<c.scrollSensitivity&&(this.scrollParent[0].scrollLeft=d=this.scrollParent[0].scrollLeft-c.scrollSpeed)):(b.pageY-a(document).scrollTop()<c.scrollSensitivity?d=a(document).scrollTop(a(document).scrollTop()-c.scrollSpeed):a(window).height()-(b.pageY-a(document).scrollTop())<c.scrollSensitivity&&(d=a(document).scrollTop(a(document).scrollTop()+c.scrollSpeed)),b.pageX-a(document).scrollLeft()<c.scrollSensitivity?d=a(document).scrollLeft(a(document).scrollLeft()-c.scrollSpeed):a(window).width()-(b.pageX-a(document).scrollLeft())<c.scrollSensitivity&&(d=a(document).scrollLeft(a(document).scrollLeft()+c.scrollSpeed))),d!==!1&&a.ui.ddmanager&&!c.dropBehaviour&&a.ui.ddmanager.prepareOffsets(this,b)}this.positionAbs=this._convertPositionTo("absolute");if(!this.options.axis||this.options.axis!="y")this.helper[0].style.left=this.position.left+"px";if(!this.options.axis||this.options.axis!="x")this.helper[0].style.top=this.position.top+"px";for(var e=this.items.length-1;e>=0;e--){var f=this.items[e],g=f.item[0],h=this._intersectsWithPointer(f);if(!h)continue;if(f.instance!==this.currentContainer)continue;if(g!=this.currentItem[0]&&this.placeholder[h==1?"next":"prev"]()[0]!=g&&!a.ui.contains(this.placeholder[0],g)&&(this.options.type=="semi-dynamic"?!a.ui.contains(this.element[0],g):!0)){this.direction=h==1?"down":"up";if(this.options.tolerance=="pointer"||this._intersectsWithSides(f))this._rearrange(b,f);else break;this._trigger("change",b,this._uiHash());break}}return this._contactContainers(b),a.ui.ddmanager&&a.ui.ddmanager.drag(this,b),this._trigger("sort",b,this._uiHash()),this.lastPositionAbs=this.positionAbs,!1},_mouseStop:function(b,c){if(!b)return;a.ui.ddmanager&&!this.options.dropBehaviour&&a.ui.ddmanager.drop(this,b);if(this.options.revert){var d=this,e=d.placeholder.offset();d.reverting=!0,a(this.helper).animate({left:e.left-this.offset.parent.left-d.margins.left+(this.offsetParent[0]==document.body?0:this.offsetParent[0].scrollLeft),top:e.top-this.offset.parent.top-d.margins.top+(this.offsetParent[0]==document.body?0:this.offsetParent[0].scrollTop)},parseInt(this.options.revert,10)||500,function(){d._clear(b)})}else this._clear(b,c);return!1},cancel:function(){var b=this;if(this.dragging){this._mouseUp({target:null}),this.options.helper=="original"?this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper"):this.currentItem.show();for(var c=this.containers.length-1;c>=0;c--)this.containers[c]._trigger("deactivate",null,b._uiHash(this)),this.containers[c].containerCache.over&&(this.containers[c]._trigger("out",null,b._uiHash(this)),this.containers[c].containerCache.over=0)}return this.placeholder&&(this.placeholder[0].parentNode&&this.placeholder[0].parentNode.removeChild(this.placeholder[0]),this.options.helper!="original"&&this.helper&&this.helper[0].parentNode&&this.helper.remove(),a.extend(this,{helper:null,dragging:!1,reverting:!1,_noFinalSort:null}),this.domPosition.prev?a(this.domPosition.prev).after(this.currentItem):a(this.domPosition.parent).prepend(this.currentItem)),this},serialize:function(b){var c=this._getItemsAsjQuery(b&&b.connected),d=[];return b=b||{},a(c).each(function(){var c=(a(b.item||this).attr(b.attribute||"id")||"").match(b.expression||/(.+)[-=_](.+)/);c&&d.push((b.key||c[1]+"[]")+"="+(b.key&&b.expression?c[1]:c[2]))}),!d.length&&b.key&&d.push(b.key+"="),d.join("&")},toArray:function(b){var c=this._getItemsAsjQuery(b&&b.connected),d=[];return b=b||{},c.each(function(){d.push(a(b.item||this).attr(b.attribute||"id")||"")}),d},_intersectsWith:function(a){var b=this.positionAbs.left,c=b+this.helperProportions.width,d=this.positionAbs.top,e=d+this.helperProportions.height,f=a.left,g=f+a.width,h=a.top,i=h+a.height,j=this.offset.click.top,k=this.offset.click.left,l=d+j>h&&d+j<i&&b+k>f&&b+k<g;return this.options.tolerance=="pointer"||this.options.forcePointerForContainers||this.options.tolerance!="pointer"&&this.helperProportions[this.floating?"width":"height"]>a[this.floating?"width":"height"]?l:f<b+this.helperProportions.width/2&&c-this.helperProportions.width/2<g&&h<d+this.helperProportions.height/2&&e-this.helperProportions.height/2<i},_intersectsWithPointer:function(b){var c=this.options.axis==="x"||a.ui.isOverAxis(this.positionAbs.top+this.offset.click.top,b.top,b.height),d=this.options.axis==="y"||a.ui.isOverAxis(this.positionAbs.left+this.offset.click.left,b.left,b.width),e=c&&d,f=this._getDragVerticalDirection(),g=this._getDragHorizontalDirection();return e?this.floating?g&&g=="right"||f=="down"?2:1:f&&(f=="down"?2:1):!1},_intersectsWithSides:function(b){var c=a.ui.isOverAxis(this.positionAbs.top+this.offset.click.top,b.top+b.height/2,b.height),d=a.ui.isOverAxis(this.positionAbs.left+this.offset.click.left,b.left+b.width/2,b.width),e=this._getDragVerticalDirection(),f=this._getDragHorizontalDirection();return this.floating&&f?f=="right"&&d||f=="left"&&!d:e&&(e=="down"&&c||e=="up"&&!c)},_getDragVerticalDirection:function(){var a=this.positionAbs.top-this.lastPositionAbs.top;return a!=0&&(a>0?"down":"up")},_getDragHorizontalDirection:function(){var a=this.positionAbs.left-this.lastPositionAbs.left;return a!=0&&(a>0?"right":"left")},refresh:function(a){return this._refreshItems(a),this.refreshPositions(),this},_connectWith:function(){var a=this.options;return a.connectWith.constructor==String?[a.connectWith]:a.connectWith},_getItemsAsjQuery:function(b){var c=this,d=[],e=[],f=this._connectWith();if(f&&b)for(var g=f.length-1;g>=0;g--){var h=a(f[g]);for(var i=h.length-1;i>=0;i--){var j=a.data(h[i],this.widgetName);j&&j!=this&&!j.options.disabled&&e.push([a.isFunction(j.options.items)?j.options.items.call(j.element):a(j.options.items,j.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"),j])}}e.push([a.isFunction(this.options.items)?this.options.items.call(this.element,null,{options:this.options,item:this.currentItem}):a(this.options.items,this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"),this]);for(var g=e.length-1;g>=0;g--)e[g][0].each(function(){d.push(this)});return a(d)},_removeCurrentsFromItems:function(){var a=this.currentItem.find(":data("+this.widgetName+"-item)");for(var b=0;b<this.items.length;b++)for(var c=0;c<a.length;c++)a[c]==this.items[b].item[0]&&this.items.splice(b,1)},_refreshItems:function(b){this.items=[],this.containers=[this];var c=this.items,d=this,e=[[a.isFunction(this.options.items)?this.options.items.call(this.element[0],b,{item:this.currentItem}):a(this.options.items,this.element),this]],f=this._connectWith();if(f&&this.ready)for(var g=f.length-1;g>=0;g--){var h=a(f[g]);for(var i=h.length-1;i>=0;i--){var j=a.data(h[i],this.widgetName);j&&j!=this&&!j.options.disabled&&(e.push([a.isFunction(j.options.items)?j.options.items.call(j.element[0],b,{item:this.currentItem}):a(j.options.items,j.element),j]),this.containers.push(j))}}for(var g=e.length-1;g>=0;g--){var k=e[g][1],l=e[g][0];for(var i=0,m=l.length;i<m;i++){var n=a(l[i]);n.data(this.widgetName+"-item",k),c.push({item:n,instance:k,width:0,height:0,left:0,top:0})}}},refreshPositions:function(b){this.offsetParent&&this.helper&&(this.offset.parent=this._getParentOffset());for(var c=this.items.length-1;c>=0;c--){var d=this.items[c];if(d.instance!=this.currentContainer&&this.currentContainer&&d.item[0]!=this.currentItem[0])continue;var e=this.options.toleranceElement?a(this.options.toleranceElement,d.item):d.item;b||(d.width=e.outerWidth(),d.height=e.outerHeight());var f=e.offset();d.left=f.left,d.top=f.top}if(this.options.custom&&this.options.custom.refreshContainers)this.options.custom.refreshContainers.call(this);else for(var c=this.containers.length-1;c>=0;c--){var f=this.containers[c].element.offset();this.containers[c].containerCache.left=f.left,this.containers[c].containerCache.top=f.top,this.containers[c].containerCache.width=this.containers[c].element.outerWidth(),this.containers[c].containerCache.height=this.containers[c].element.outerHeight()}return this},_createPlaceholder:function(b){var c=b||this,d=c.options;if(!d.placeholder||d.placeholder.constructor==String){var e=d.placeholder;d.placeholder={element:function(){var b=a(document.createElement(c.currentItem[0].nodeName)).addClass(e||c.currentItem[0].className+" ui-sortable-placeholder").removeClass("ui-sortable-helper")[0];return e||(b.style.visibility="hidden"),b},update:function(a,b){if(e&&!d.forcePlaceholderSize)return;b.height()||b.height(c.currentItem.innerHeight()-parseInt(c.currentItem.css("paddingTop")||0,10)-parseInt(c.currentItem.css("paddingBottom")||0,10)),b.width()||b.width(c.currentItem.innerWidth()-parseInt(c.currentItem.css("paddingLeft")||0,10)-parseInt(c.currentItem.css("paddingRight")||0,10))}}}c.placeholder=a(d.placeholder.element.call(c.element,c.currentItem)),c.currentItem.after(c.placeholder),d.placeholder.update(c,c.placeholder)},_contactContainers:function(b){var c=null,d=null;for(var e=this.containers.length-1;e>=0;e--){if(a.ui.contains(this.currentItem[0],this.containers[e].element[0]))continue;if(this._intersectsWith(this.containers[e].containerCache)){if(c&&a.ui.contains(this.containers[e].element[0],c.element[0]))continue;c=this.containers[e],d=e}else this.containers[e].containerCache.over&&(this.containers[e]._trigger("out",b,this._uiHash(this)),this.containers[e].containerCache.over=0)}if(!c)return;if(this.containers.length===1)this.containers[d]._trigger("over",b,this._uiHash(this)),this.containers[d].containerCache.over=1;else if(this.currentContainer!=this.containers[d]){var f=1e4,g=null,h=this.positionAbs[this.containers[d].floating?"left":"top"];for(var i=this.items.length-1;i>=0;i--){if(!a.ui.contains(this.containers[d].element[0],this.items[i].item[0]))continue;var j=this.containers[d].floating?this.items[i].item.offset().left:this.items[i].item.offset().top;Math.abs(j-h)<f&&(f=Math.abs(j-h),g=this.items[i],this.direction=j-h>0?"down":"up")}if(!g&&!this.options.dropOnEmpty)return;this.currentContainer=this.containers[d],g?this._rearrange(b,g,null,!0):this._rearrange(b,null,this.containers[d].element,!0),this._trigger("change",b,this._uiHash()),this.containers[d]._trigger("change",b,this._uiHash(this)),this.options.placeholder.update(this.currentContainer,this.placeholder),this.containers[d]._trigger("over",b,this._uiHash(this)),this.containers[d].containerCache.over=1}},_createHelper:function(b){var c=this.options,d=a.isFunction(c.helper)?a(c.helper.apply(this.element[0],[b,this.currentItem])):c.helper=="clone"?this.currentItem.clone():this.currentItem;return d.parents("body").length||a(c.appendTo!="parent"?c.appendTo:this.currentItem[0].parentNode)[0].appendChild(d[0]),d[0]==this.currentItem[0]&&(this._storedCSS={width:this.currentItem[0].style.width,height:this.currentItem[0].style.height,position:this.currentItem.css("position"),top:this.currentItem.css("top"),left:this.currentItem.css("left")}),(d[0].style.width==""||c.forceHelperSize)&&d.width(this.currentItem.width()),(d[0].style.height==""||c.forceHelperSize)&&d.height(this.currentItem.height()),d},_adjustOffsetFromHelper:function(b){typeof b=="string"&&(b=b.split(" ")),a.isArray(b)&&(b={left:+b[0],top:+b[1]||0}),"left"in b&&(this.offset.click.left=b.left+this.margins.left),"right"in b&&(this.offset.click.left=this.helperProportions.width-b.right+this.margins.left),"top"in b&&(this.offset.click.top=b.top+this.margins.top),"bottom"in b&&(this.offset.click.top=this.helperProportions.height-b.bottom+this.margins.top)},_getParentOffset:function(){this.offsetParent=this.helper.offsetParent();var b=this.offsetParent.offset();this.cssPosition=="absolute"&&this.scrollParent[0]!=document&&a.ui.contains(this.scrollParent[0],this.offsetParent[0])&&(b.left+=this.scrollParent.scrollLeft(),b.top+=this.scrollParent.scrollTop());if(this.offsetParent[0]==document.body||this.offsetParent[0].tagName&&this.offsetParent[0].tagName.toLowerCase()=="html"&&a.browser.msie)b={top:0,left:0};return{top:b.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:b.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)}},_getRelativeOffset:function(){if(this.cssPosition=="relative"){var a=this.currentItem.position();return{top:a.top-(parseInt(this.helper.css("top"),10)||0)+this.scrollParent.scrollTop(),left:a.left-(parseInt(this.helper.css("left"),10)||0)+this.scrollParent.scrollLeft()}}return{top:0,left:0}},_cacheMargins:function(){this.margins={left:parseInt(this.currentItem.css("marginLeft"),10)||0,top:parseInt(this.currentItem.css("marginTop"),10)||0}},_cacheHelperProportions:function(){this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()}},_setContainment:function(){var b=this.options;b.containment=="parent"&&(b.containment=this.helper[0].parentNode);if(b.containment=="document"||b.containment=="window")this.containment=[0-this.offset.relative.left-this.offset.parent.left,0-this.offset.relative.top-this.offset.parent.top,a(b.containment=="document"?document:window).width()-this.helperProportions.width-this.margins.left,(a(b.containment=="document"?document:window).height()||document.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top];if(!/^(document|window|parent)$/.test(b.containment)){var c=a(b.containment)[0],d=a(b.containment).offset(),e=a(c).css("overflow")!="hidden";this.containment=[d.left+(parseInt(a(c).css("borderLeftWidth"),10)||0)+(parseInt(a(c).css("paddingLeft"),10)||0)-this.margins.left,d.top+(parseInt(a(c).css("borderTopWidth"),10)||0)+(parseInt(a(c).css("paddingTop"),10)||0)-this.margins.top,d.left+(e?Math.max(c.scrollWidth,c.offsetWidth):c.offsetWidth)-(parseInt(a(c).css("borderLeftWidth"),10)||0)-(parseInt(a(c).css("paddingRight"),10)||0)-this.helperProportions.width-this.margins.left,d.top+(e?Math.max(c.scrollHeight,c.offsetHeight):c.offsetHeight)-(parseInt(a(c).css("borderTopWidth"),10)||0)-(parseInt(a(c).css("paddingBottom"),10)||0)-this.helperProportions.height-this.margins.top]}},_convertPositionTo:function(b,c){c||(c=this.position);var d=b=="absolute"?1:-1,e=this.options,f=this.cssPosition=="absolute"&&(this.scrollParent[0]==document||!a.ui.contains(this.scrollParent[0],this.offsetParent[0]))?this.offsetParent:this.scrollParent,g=/(html|body)/i.test(f[0].tagName);return{top:c.top+this.offset.relative.top*d+this.offset.parent.top*d-(a.browser.safari&&this.cssPosition=="fixed"?0:(this.cssPosition=="fixed"?-this.scrollParent.scrollTop():g?0:f.scrollTop())*d),left:c.left+this.offset.relative.left*d+this.offset.parent.left*d-(a.browser.safari&&this.cssPosition=="fixed"?0:(this.cssPosition=="fixed"?-this.scrollParent.scrollLeft():g?0:f.scrollLeft())*d)}},_generatePosition:function(b){var c=this.options,d=this.cssPosition=="absolute"&&(this.scrollParent[0]==document||!a.ui.contains(this.scrollParent[0],this.offsetParent[0]))?this.offsetParent:this.scrollParent,e=/(html|body)/i.test(d[0].tagName);this.cssPosition=="relative"&&(this.scrollParent[0]==document||this.scrollParent[0]==this.offsetParent[0])&&(this.offset.relative=this._getRelativeOffset());var f=b.pageX,g=b.pageY;if(this.originalPosition){this.containment&&(b.pageX-this.offset.click.left<this.containment[0]&&(f=this.containment[0]+this.offset.click.left),b.pageY-this.offset.click.top<this.containment[1]&&(g=this.containment[1]+this.offset.click.top),b.pageX-this.offset.click.left>this.containment[2]&&(f=this.containment[2]+this.offset.click.left),b.pageY-this.offset.click.top>this.containment[3]&&(g=this.containment[3]+this.offset.click.top));if(c.grid){var h=this.originalPageY+Math.round((g-this.originalPageY)/c.grid[1])*c.grid[1];g=this.containment?h-this.offset.click.top<this.containment[1]||h-this.offset.click.top>this.containment[3]?h-this.offset.click.top<this.containment[1]?h+c.grid[1]:h-c.grid[1]:h:h;var i=this.originalPageX+Math.round((f-this.originalPageX)/c.grid[0])*c.grid[0];f=this.containment?i-this.offset.click.left<this.containment[0]||i-this.offset.click.left>this.containment[2]?i-this.offset.click.left<this.containment[0]?i+c.grid[0]:i-c.grid[0]:i:i}}return{top:g-this.offset.click.top-this.offset.relative.top-this.offset.parent.top+(a.browser.safari&&this.cssPosition=="fixed"?0:this.cssPosition=="fixed"?-this.scrollParent.scrollTop():e?0:d.scrollTop()),left:f-this.offset.click.left-this.offset.relative.left-this.offset.parent.left+(a.browser.safari&&this.cssPosition=="fixed"?0:this.cssPosition=="fixed"?-this.scrollParent.scrollLeft():e?0:d.scrollLeft())}},_rearrange:function(a,b,c,d){c?c[0].appendChild(this.placeholder[0]):b.item[0].parentNode.insertBefore(this.placeholder[0],this.direction=="down"?b.item[0]:b.item[0].nextSibling),this.counter=this.counter?++this.counter:1;var e=this,f=this.counter;window.setTimeout(function(){f==e.counter&&e.refreshPositions(!d)},0)},_clear:function(b,c){this.reverting=!1;var d=[],e=this;!this._noFinalSort&&this.currentItem.parent().length&&this.placeholder.before(this.currentItem),this._noFinalSort=null;if(this.helper[0]==this.currentItem[0]){for(var f in this._storedCSS)if(this._storedCSS[f]=="auto"||this._storedCSS[f]=="static")this._storedCSS[f]="";this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper")}else this.currentItem.show();this.fromOutside&&!c&&d.push(function(a){this._trigger("receive",a,this._uiHash(this.fromOutside))}),(this.fromOutside||this.domPosition.prev!=this.currentItem.prev().not(".ui-sortable-helper")[0]||this.domPosition.parent!=this.currentItem.parent()[0])&&!c&&d.push(function(a){this._trigger("update",a,this._uiHash())}),this!==this.currentContainer&&(c||(d.push(function(a){this._trigger("remove",a,this._uiHash())}),d.push(function(a){return function(b){a._trigger("receive",b,this._uiHash(this))}}.call(this,this.currentContainer)),d.push(function(a){return function(b){a._trigger("update",b,this._uiHash(this))}}.call(this,this.currentContainer))));for(var f=this.containers.length-1;f>=0;f--)c||d.push(function(a){return function(b){a._trigger("deactivate",b,this._uiHash(this))}}.call(this,this.containers[f])),this.containers[f].containerCache.over&&(d.push(function(a){return function(b){a._trigger("out",b,this._uiHash(this))}}.call(this,this.containers[f])),this.containers[f].containerCache.over=0);this._storedCursor&&a("body").css("cursor",this._storedCursor),this._storedOpacity&&this.helper.css("opacity",this._storedOpacity),this._storedZIndex&&this.helper.css("zIndex",this._storedZIndex=="auto"?"":this._storedZIndex),this.dragging=!1;if(this.cancelHelperRemoval){if(!c){this._trigger("beforeStop",b,this._uiHash());for(var f=0;f<d.length;f++)d[f].call(this,b);this._trigger("stop",b,this._uiHash())}return this.fromOutside=!1,!1}c||this._trigger("beforeStop",b,this._uiHash()),this.placeholder[0].parentNode.removeChild(this.placeholder[0]),this.helper[0]!=this.currentItem[0]&&this.helper.remove(),this.helper=null;if(!c){for(var f=0;f<d.length;f++)d[f].call(this,b);this._trigger("stop",b,this._uiHash())}return this.fromOutside=!1,!0},_trigger:function(){a.Widget.prototype._trigger.apply(this,arguments)===!1&&this.cancel()},_uiHash:function(b){var c=b||this;return{helper:c.helper,placeholder:c.placeholder||a([]),position:c.position,originalPosition:c.originalPosition,offset:c.positionAbs,item:c.currentItem,sender:b?b.element:null}}}),a.extend(a.ui.sortable,{version:"1.8.24"})})(jQuery);;
/*
Copyright (c) 2011 Gary Linscott
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions
are met:
1. Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.
3. The name of the author may not be used to endorse or promote products
   derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
"use strict";

// Perf TODO:
// Merge material updating with psq values
// Put move scoring inline in generator
// Remove need for fliptable in psq tables.  Access them by color
// Optimize pawn move generation

// Non-perf todo:
// Checks in first q?
// Pawn eval.
// Better king evaluation
// Better move sorting in PV nodes (especially root)

var g_debug = true;
var g_timeout = 40;

function GetFen(){
    var result = "";
    for (var row = 0; row < 8; row++) {
        if (row != 0) 
            result += '/';
        var empty = 0;
        for (var col = 0; col < 8; col++) {
            var piece = g_board[((row + 2) << 4) + col + 4];
            if (piece == 0) {
                empty++;
            }
            else {
                if (empty != 0) 
                    result += empty;
                empty = 0;
                
                var pieceChar = [" ", "p", "n", "b", "r", "q", "k", " "][(piece & 0x7)];
                result += ((piece & colorWhite) != 0) ? pieceChar.toUpperCase() : pieceChar;
            }
        }
        if (empty != 0) {
            result += empty;
        }
    }
    
    result += g_toMove == colorWhite ? " w" : " b";
    result += " ";
    if (g_castleRights == 0) {
        result += "-";
    }
    else {
        if ((g_castleRights & 1) != 0) 
            result += "K";
        if ((g_castleRights & 2) != 0) 
            result += "Q";
        if ((g_castleRights & 4) != 0) 
            result += "k";
        if ((g_castleRights & 8) != 0) 
            result += "q";
    }
    
    result += " ";
    
    if (g_enPassentSquare == -1) {
        result += '-';
    }
    else {
        result += FormatSquare(g_enPassentSquare);
    }
    
    return result;
}

function GetMoveSAN(move, validMoves) {

 if (move == NULLMOVE) return '--';

	var token = '';
	MakeMove(move);
	var ile = GenerateValidMoves().length;
	if (g_inCheck)
	{
	 token = ile == 0 ? "#" : "+";
	}
       else if (ile == 0) token = '$';
	UnmakeMove(move);

	if (move & moveflagCastleKing)  return   "O-O" + token;
	if (move & moveflagCastleQueen) return "O-O-O" + token;

	var from = move & 0xFF;
	var to = (move >> 8) & 0xFF;
		
	var pieceType = g_board[from] & 0x7;
	var result = ["", "", "N", "B", "R", "Q", "K", ""][pieceType];
	
	var dupe = false, rowDiff = true, colDiff = true;
	if (validMoves == null) {
		validMoves = GenerateValidMoves();
	}
	for (var i = 0; i < validMoves.length; i++) {
		var moveFrom = validMoves[i] & 0xFF;
		var moveTo = (validMoves[i] >> 8) & 0xFF; 
		if (moveFrom != from &&
			moveTo == to &&
			(g_board[moveFrom] & 0x7) == pieceType) {
			dupe = true;
			if ((moveFrom & 0xF0) == (from & 0xF0)) {
				rowDiff = false;
			}
			if ((moveFrom & 0x0F) == (from & 0x0F)) {
				colDiff = false;
			}
		}
	}
	
	if (dupe) {
		if (colDiff) {
			result += FormatSquare(from).charAt(0);
		} else if (rowDiff) {
			result += FormatSquare(from).charAt(1);
		} else {
			result += FormatSquare(from);
		}
	} else if (pieceType == piecePawn && (g_board[to] != 0 || (move & moveflagEPC))) {
		result += FormatSquare(from).charAt(0);
	}
	
	if (g_board[to] != 0 || (move & moveflagEPC)) {
		result += "x";
	}
	
	result += FormatSquare(to);
	
	if (move & moveflagPromotion) {
		if (move & moveflagPromoteBishop) result += "=B";
		else if (move & moveflagPromoteKnight) result += "=N";
		else if (move & moveflagPromoteQueen) result += "=Q";
		else result += "=R";
	}

	return result + token;
}

function FormatSquare(square) {
    var letters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
    return letters[(square & 0xF) - 4] + ((9 - (square >> 4)) + 1);
}

function FormatMove(move) {
	if (move == NULLMOVE) return '--';
    var result = FormatSquare(move & 0xFF) + FormatSquare((move >> 8) & 0xFF);
    if (move & moveflagPromotion) {
        if (move & moveflagPromoteBishop) result += "b";
        else if (move & moveflagPromoteKnight) result += "n";
        else if (move & moveflagPromoteQueen) result += "q";
        else result += "r";
    }
    return result;
}

function GetMoveFromString(moveString) {
	if (moveString == '--') return NULLMOVE;
    var moves = GenerateValidMoves();
    for (var i = 0; i < moves.length; i++) {
        if (FormatMove(moves[i]) == moveString) {
            return moves[i];
        }
    }
    //alert("busted! ->" + moveString + " fen:" + GetFen());
	//galog('busted',moveString + " " + location.href);
	return 'busted';
}

function PVFromHash(move, ply) {
    if (ply == 0) 
        return "";

    if (move == 0) {
	if (g_inCheck) return "checkmate";
	return "stalemate";
    }
    
    var pvString = " " + GetMoveSAN(move);
    MakeMove(move);
    
    var hashNode = g_hashTable[g_hashKeyLow & g_hashMask];
    if (hashNode != null && hashNode.lock == g_hashKeyHigh && hashNode.bestMove != null) {
        pvString += PVFromHash(hashNode.bestMove, ply - 1);
    }
    
    UnmakeMove(move);
    
    return pvString;
}

//
// Searching code
//

var g_startTime;

var g_nodeCount;
var g_qNodeCount;
var g_searchValid;
var g_globalPly = 0;

function Search(finishMoveCallback, maxPly, finishPlyCallback) {
    var lastEval;
    var alpha = minEval;
    var beta = maxEval;
    
	g_globalPly++;
    g_nodeCount = 0;
    g_qNodeCount = 0;
    g_searchValid = true;
    
    var bestMove = 0;
    var value;
    
    g_startTime = (new Date()).getTime();

    var i;
    for (i = 1; i <= maxPly && g_searchValid; i++) {
        var tmp = AlphaBeta(i, 0, alpha, beta);
        if (!g_searchValid) break;

        value = tmp;

        if (value > alpha && value < beta) {
            alpha = value - 500;
            beta = value + 500;

            if (alpha < minEval) alpha = minEval;
            if (beta > maxEval) beta = maxEval;
        } else if (alpha != minEval) {
            alpha = minEval;
            beta = maxEval;
            i--;
        }

        if (g_hashTable[g_hashKeyLow & g_hashMask] != null) {
            bestMove = g_hashTable[g_hashKeyLow & g_hashMask].bestMove;
        }

        if (finishPlyCallback != null) {
            finishPlyCallback(bestMove, value, (new Date()).getTime() - g_startTime, i);
        }
    }

    if (finishMoveCallback != null) {
        finishMoveCallback(bestMove, value, (new Date()).getTime() - g_startTime, i - 1);
    }
}

var minEval = -2000000;
var maxEval = +2000000;

var minMateBuffer = minEval + 2000;
var maxMateBuffer = maxEval - 2000;

var materialTable = [0, 800, 3350, 3450, 5000, 9750, 600000];

var pawnAdj =
[
  0, 0, 0, 0, 0, 0, 0, 0,
  -25, 105, 135, 270, 270, 135, 105, -25,
  -80, 0, 30, 176, 176, 30, 0, -80,
  -85, -5, 25, 175, 175, 25, -5, -85,
  -90, -10, 20, 125, 125, 20, -10, -90,
  -95, -15, 15, 75, 75, 15, -15, -95, 
  -100, -20, 10, 70, 70, 10, -20, -100, 
  0, 0, 0, 0, 0, 0, 0, 0
];

var knightAdj =
    [-200, -100, -50, -50, -50, -50, -100, -200,
      -100, 0, 0, 0, 0, 0, 0, -100,
      -50, 0, 60, 60, 60, 60, 0, -50,
      -50, 0, 30, 60, 60, 30, 0, -50,
      -50, 0, 30, 60, 60, 30, 0, -50,
      -50, 0, 30, 30, 30, 30, 0, -50,
      -100, 0, 0, 0, 0, 0, 0, -100,
      -200, -50, -25, -25, -25, -25, -50, -200
     ];

var bishopAdj =
    [ -50,-50,-25,-10,-10,-25,-50,-50,
      -50,-25,-10,  0,  0,-10,-25,-50,
      -25,-10,  0, 25, 25,  0,-10,-25,
      -10,  0, 25, 40, 40, 25,  0,-10,
      -10,  0, 25, 40, 40, 25,  0,-10,
      -25,-10,  0, 25, 25,  0,-10,-25,
      -50,-25,-10,  0,  0,-10,-25,-50,
      -50,-50,-25,-10,-10,-25,-50,-50
     ];

var rookAdj =
    [ -60, -30, -10, 20, 20, -10, -30, -60,
       40,  70,  90,120,120,  90,  70,  40,
      -60, -30, -10, 20, 20, -10, -30, -60,
      -60, -30, -10, 20, 20, -10, -30, -60,
      -60, -30, -10, 20, 20, -10, -30, -60,
      -60, -30, -10, 20, 20, -10, -30, -60,
      -60, -30, -10, 20, 20, -10, -30, -60,
      -60, -30, -10, 20, 20, -10, -30, -60
     ];

var kingAdj =
    [  50, 150, -25, -125, -125, -25, 150, 50,
       50, 150, -25, -125, -125, -25, 150, 50,
       50, 150, -25, -125, -125, -25, 150, 50,
       50, 150, -25, -125, -125, -25, 150, 50,
       50, 150, -25, -125, -125, -25, 150, 50,
       50, 150, -25, -125, -125, -25, 150, 50,
       50, 150, -25, -125, -125, -25, 150, 50,
      150, 250, 75, -25, -25, 75, 250, 150
     ];

var emptyAdj =
    [0, 0, 0, 0, 0, 0, 0, 0, 
        0, 0, 0, 0, 0, 0, 0, 0, 
        0, 0, 0, 0, 0, 0, 0, 0, 
        0, 0, 0, 0, 0, 0, 0, 0, 
        0, 0, 0, 0, 0, 0, 0, 0, 
        0, 0, 0, 0, 0, 0, 0, 0, 
        0, 0, 0, 0, 0, 0, 0, 0, 
        0, 0, 0, 0, 0, 0, 0, 0, 
     ];

var pieceSquareAdj = new Array(8);

// Returns the square flipped
var flipTable = new Array(256);

function PawnEval(color) {
    var pieceIdx = (color | 1) << 4;
    var from = g_pieceList[pieceIdx++];
    while (from != 0) {
        from = g_pieceList[pieceIdx++];
    }
}

function Mobility(color) {
    var result = 0;
    var from, to, mob, pieceIdx;
    var enemy = color == 8 ? 0x10 : 0x8
    var mobUnit = color == 8 ? g_mobUnit[0] : g_mobUnit[1];

    // Knight mobility
    mob = -3;
    pieceIdx = (color | 2) << 4;
    from = g_pieceList[pieceIdx++];
    while (from != 0) {
        mob += mobUnit[g_board[from + 31]];
        mob += mobUnit[g_board[from + 33]];
        mob += mobUnit[g_board[from + 14]];
        mob += mobUnit[g_board[from - 14]];
        mob += mobUnit[g_board[from - 31]];
        mob += mobUnit[g_board[from - 33]];
        mob += mobUnit[g_board[from + 18]];
        mob += mobUnit[g_board[from - 18]];
        from = g_pieceList[pieceIdx++];
    }
    result += 65 * mob;

    // Bishop mobility
    mob = -4;
    pieceIdx = (color | 3) << 4;
    from = g_pieceList[pieceIdx++];
    while (from != 0) {
        to = from - 15; while (g_board[to] == 0) { to -= 15; mob++; }
        if (g_board[to] & enemy) {
          mob++;
          if (!(g_board[to] & piecePawn)) {
            to -= 15; while (g_board[to] == 0) to -= 15;
            mob += mobUnit[g_board[to]] << 2;
          }
        }

        to = from - 17; while (g_board[to] == 0) { to -= 17; mob++; }
        if (g_board[to] & enemy) {
          mob++;
          if (!(g_board[to] & piecePawn)) {
            to -= 17; while (g_board[to] == 0) to -= 17;
            mob += mobUnit[g_board[to]] << 2; 
          }
        }

        to = from + 15; while (g_board[to] == 0) { to += 15; mob++; }
        if (g_board[to] & enemy) {
          mob++;
          if (!(g_board[to] & piecePawn)) {
            to += 15; while (g_board[to] == 0) to += 15;
            mob += mobUnit[g_board[to]] << 2; 
          }
        }

        to = from + 17; while (g_board[to] == 0) { to += 17; mob++; }
        if (g_board[to] & enemy) {
          mob++;
          if (!(g_board[to] & piecePawn)) {
            to += 17; while (g_board[to] == 0) to += 17;
            mob += mobUnit[g_board[to]] << 2;
          }
        }

        from = g_pieceList[pieceIdx++];
    }
    result += 44 * mob;

    // Rook mobility
    mob = -4;
    pieceIdx = (color | 4) << 4;
    from = g_pieceList[pieceIdx++];
    while (from != 0) {
        to = from - 1; while (g_board[to] == 0) { to--; mob++;}  if (g_board[to] & enemy) mob++;
        to = from + 1; while (g_board[to] == 0) { to++; mob++; } if (g_board[to] & enemy) mob++;
        to = from + 16; while (g_board[to] == 0) { to += 16; mob++; } if (g_board[to] & enemy) mob++;
        to = from - 16; while (g_board[to] == 0) { to -= 16; mob++; } if (g_board[to] & enemy) mob++;
        from = g_pieceList[pieceIdx++];
    }
    result += 25 * mob;

    // Queen mobility
    mob = -2;
    pieceIdx = (color | 5) << 4;
    from = g_pieceList[pieceIdx++];
    while (from != 0) {
        to = from - 15; while (g_board[to] == 0) { to -= 15; mob++; } if (g_board[to] & enemy) mob++;
        to = from - 17; while (g_board[to] == 0) { to -= 17; mob++; } if (g_board[to] & enemy) mob++;
        to = from + 15; while (g_board[to] == 0) { to += 15; mob++; } if (g_board[to] & enemy) mob++;
        to = from + 17; while (g_board[to] == 0) { to += 17; mob++; } if (g_board[to] & enemy) mob++;
        to = from - 1; while (g_board[to] == 0) { to--; mob++; } if (g_board[to] & enemy) mob++;
        to = from + 1; while (g_board[to] == 0) { to++; mob++; } if (g_board[to] & enemy) mob++;
        to = from + 16; while (g_board[to] == 0) { to += 16; mob++; } if (g_board[to] & enemy) mob++;
        to = from - 16; while (g_board[to] == 0) { to -= 16; mob++; } if (g_board[to] & enemy) mob++;
        from = g_pieceList[pieceIdx++];
    }
    result += 22 * mob;

    return result;
}

function Evaluate() {
    var curEval = g_baseEval;

    var evalAdjust = 0;
    // Black queen gone, then cancel white's penalty for king movement
    if (g_pieceList[pieceQueen << 4] == 0)
        evalAdjust -= pieceSquareAdj[pieceKing][g_pieceList[(colorWhite | pieceKing) << 4]];
    // White queen gone, then cancel black's penalty for king movement
    if (g_pieceList[(colorWhite | pieceQueen) << 4] == 0) 
        evalAdjust += pieceSquareAdj[pieceKing][flipTable[g_pieceList[pieceKing << 4]]];

    // Black bishop pair
    if (g_pieceCount[pieceBishop] >= 2)
        evalAdjust -= 500;
    // White bishop pair
    if (g_pieceCount[pieceBishop | colorWhite] >= 2)
        evalAdjust += 500;

    var mobility = Mobility(8) - Mobility(0);

    if (g_toMove == 0) {
        // Black
        curEval -= mobility;
        curEval -= evalAdjust;
    }
    else {
        curEval += mobility;
        curEval += evalAdjust;
    }
    
    return curEval;
}

function ScoreMove(move){
    var moveTo = (move >> 8) & 0xFF;
    var captured = g_board[moveTo] & 0x7;
    var piece = g_board[move & 0xFF];
    var score;
    if (captured != 0) {
        var pieceType = piece & 0x7;
        score = (captured << 5) - pieceType;
    } else {
        score = historyTable[piece & 0xF][moveTo];
    }
    return score;
}

function QSearch(alpha, beta, ply) {
    g_qNodeCount++;

    var realEval = g_inCheck ? (minEval + 1) : Evaluate();
    
    if (realEval >= beta) 
        return realEval;

    if (realEval > alpha)
        alpha = realEval;

    var moves = new Array();
    var moveScores = new Array();
    var wasInCheck = g_inCheck;

    if (wasInCheck) {
        // TODO: Fast check escape generator and fast checking moves generator
        GenerateCaptureMoves(moves, null);
        GenerateAllMoves(moves);

        for (var i = 0; i < moves.length; i++) {
            moveScores[i] = ScoreMove(moves[i]);
        }
    } else {
        GenerateCaptureMoves(moves, null);

        for (var i = 0; i < moves.length; i++) {
            var captured = g_board[(moves[i] >> 8) & 0xFF] & 0x7;
            var pieceType = g_board[moves[i] & 0xFF] & 0x7;

            moveScores[i] = (captured << 5) - pieceType;
        }
    }

    for (var i = 0; i < moves.length; i++) {
        var bestMove = i;
        for (var j = moves.length - 1; j > i; j--) {
            if (moveScores[j] > moveScores[bestMove]) {
                bestMove = j;
            }
        }
        {
            var tmpMove = moves[i];
            moves[i] = moves[bestMove];
            moves[bestMove] = tmpMove;
            
            var tmpScore = moveScores[i];
            moveScores[i] = moveScores[bestMove];
            moveScores[bestMove] = tmpScore;
        }

        if (!wasInCheck && !See(moves[i])) {
            continue;
        }

        if (!MakeMove(moves[i])) {
            continue;
        }

        var value = -QSearch(-beta, -alpha, ply - 1);
        
        UnmakeMove(moves[i]);
        
        if (value > realEval) {
            if (value >= beta) 
                return value;
            
            if (value > alpha)
                alpha = value;
            
            realEval = value;
        }
    }

    /* Disable checks...  Too slow currently

    if (ply == 0 && !wasInCheck) {
        moves = new Array();
        GenerateAllMoves(moves);

        for (var i = 0; i < moves.length; i++) {
            moveScores[i] = ScoreMove(moves[i]);
        }

        for (var i = 0; i < moves.length; i++) {
            var bestMove = i;
            for (var j = moves.length - 1; j > i; j--) {
                if (moveScores[j] > moveScores[bestMove]) {
                    bestMove = j;
                }
            }
            {
                var tmpMove = moves[i];
                moves[i] = moves[bestMove];
                moves[bestMove] = tmpMove;

                var tmpScore = moveScores[i];
                moveScores[i] = moveScores[bestMove];
                moveScores[bestMove] = tmpScore;
            }

            if (!MakeMove(moves[i])) {
                continue;
            }
            var checking = g_inCheck;
            UnmakeMove(moves[i]);

            if (!checking) {
                continue;
            }

            if (!See(moves[i])) {
                continue;
            }
            
            MakeMove(moves[i]);

            var value = -QSearch(-beta, -alpha, ply - 1);

            UnmakeMove(moves[i]);

            if (value > realEval) {
                if (value >= beta)
                    return value;

                if (value > alpha)
                    alpha = value;

                realEval = value;
            }
        }
    }
    */

    return realEval;
}

function StoreHash(value, flags, ply, move, depth) {
	if (value >= maxMateBuffer)
		value += depth;
	else if (value <= minMateBuffer)
		value -= depth;
	g_hashTable[g_hashKeyLow & g_hashMask] = new HashEntry(g_hashKeyHigh, value, flags, ply, move);
}

function IsHashMoveValid(hashMove) {
    var from = hashMove & 0xFF;
    var to = (hashMove >> 8) & 0xFF;
    var ourPiece = g_board[from];
    var pieceType = ourPiece & 0x7;
    if (pieceType < piecePawn || pieceType > pieceKing) return false;
    // Can't move a piece we don't control
    if (g_toMove != (ourPiece & 0x8))
        return false;
    // Can't move to a square that has something of the same color
    if (g_board[to] != 0 && (g_toMove == (g_board[to] & 0x8)))
        return false;
    if (pieceType == piecePawn) {
        if (hashMove & moveflagEPC) {
            return false;
        }

        // Valid moves are push, capture, double push, promotions
        var dir = to - from;
        if ((g_toMove == colorWhite) != (dir < 0))  {
            // Pawns have to move in the right direction
            return false;
        }

        var row = to & 0xF0;
        if (((row == 0x90 && !g_toMove) ||
             (row == 0x20 && g_toMove)) != (hashMove & moveflagPromotion)) {
            // Handle promotions
            return false;
        }

        if (dir == -16 || dir == 16) {
            // White/Black push
            return g_board[to] == 0;
        } else if (dir == -15 || dir == -17 || dir == 15 || dir == 17) {
            // White/Black capture
            return g_board[to] != 0;
        } else if (dir == -32) {
            // Double white push
            if (row != 0x60) return false;
            if (g_board[to] != 0) return false;
            if (g_board[from - 16] != 0) return false;
        } else if (dir == 32) {
            // Double black push
            if (row != 0x50) return false;
            if (g_board[to] != 0) return false;
            if (g_board[from + 16] != 0) return false;
        } else {
            return false;
        }

        return true;
    } else {
        // This validates that this piece type can actually make the attack
        if (hashMove >> 16) return false;
        return IsSquareAttackableFrom(to, from);
    }
}

function IsRepDraw() {
    var stop = g_moveCount - 1 - g_move50;
    stop = stop < 0 ? 0 : stop;
    for (var i = g_moveCount - 5; i >= stop; i -= 2) {
        if (g_repMoveStack[i] == g_hashKeyLow)
            return true;
    }
    return false;
}

function MovePicker(hashMove, depth, killer1, killer2) {
    this.hashMove = hashMove;
    this.depth = depth;
    this.killer1 = killer1;
    this.killer2 = killer2;

    this.moves = new Array();
    this.losingCaptures = null;
    this.moveCount = 0;
    this.atMove = -1;
    this.moveScores = null;
    this.stage = 0;

    this.nextMove = function () {
        if (++this.atMove == this.moveCount) {
            this.stage++;
            if (this.stage == 1) {
                if (this.hashMove != null && IsHashMoveValid(hashMove)) {
                    this.moves[0] = hashMove;
                    this.moveCount = 1;
                }
                if (this.moveCount != 1) {
                    this.hashMove = null;
                    this.stage++;
                }
            }

            if (this.stage == 2) {
                GenerateCaptureMoves(this.moves, null);
                this.moveCount = this.moves.length;
                this.moveScores = new Array(this.moveCount);
                // Move ordering
                for (var i = this.atMove; i < this.moveCount; i++) {
                    var captured = g_board[(this.moves[i] >> 8) & 0xFF] & 0x7;
                    var pieceType = g_board[this.moves[i] & 0xFF] & 0x7;
                    this.moveScores[i] = (captured << 5) - pieceType;
                }
                // No moves, onto next stage
                if (this.atMove == this.moveCount) this.stage++;
            }

            if (this.stage == 3) {
                if (IsHashMoveValid(this.killer1) &&
                    this.killer1 != this.hashMove) {
                    this.moves[this.moves.length] = this.killer1;
                    this.moveCount = this.moves.length;
                } else {
                    this.killer1 = 0;
                    this.stage++;
                }
            }

            if (this.stage == 4) {
                if (IsHashMoveValid(this.killer2) &&
                    this.killer2 != this.hashMove) {
                    this.moves[this.moves.length] = this.killer2;
                    this.moveCount = this.moves.length;
                } else {
                    this.killer2 = 0;
                    this.stage++;
                }
            }

            if (this.stage == 5) {
                GenerateAllMoves(this.moves);
                this.moveCount = this.moves.length;
                // Move ordering
                for (var i = this.atMove; i < this.moveCount; i++) this.moveScores[i] = ScoreMove(this.moves[i]);
                // No moves, onto next stage
                if (this.atMove == this.moveCount) this.stage++;
            }

            if (this.stage == 6) {
                // Losing captures
                if (this.losingCaptures != null) {
                    for (var i = 0; i < this.losingCaptures.length; i++) {
                        this.moves[this.moves.length] = this.losingCaptures[i];
                    }
                    for (var i = this.atMove; i < this.moveCount; i++) this.moveScores[i] = ScoreMove(this.moves[i]);
                    this.moveCount = this.moves.length;
                }
                // No moves, onto next stage
                if (this.atMove == this.moveCount) this.stage++;
            }

            if (this.stage == 7)
                return 0;
        }

        var bestMove = this.atMove;
        for (var j = this.atMove + 1; j < this.moveCount; j++) {
            if (this.moveScores[j] > this.moveScores[bestMove]) {
                bestMove = j;
            }
        }

        if (bestMove != this.atMove) {
            var tmpMove = this.moves[this.atMove];
            this.moves[this.atMove] = this.moves[bestMove];
            this.moves[bestMove] = tmpMove;

            var tmpScore = this.moveScores[this.atMove];
            this.moveScores[this.atMove] = this.moveScores[bestMove];
            this.moveScores[bestMove] = tmpScore;
        }

        var candidateMove = this.moves[this.atMove];
        if ((this.stage > 1 && candidateMove == this.hashMove) ||
            (this.stage > 3 && candidateMove == this.killer1) ||
            (this.stage > 4 && candidateMove == this.killer2)) {
            return this.nextMove();
        }

        if (this.stage == 2 && !See(candidateMove)) {
            if (this.losingCaptures == null) {
                this.losingCaptures = new Array();
            }
            this.losingCaptures[this.losingCaptures.length] = candidateMove;
            return this.nextMove();
        }

        return this.moves[this.atMove];
    }
}

function AllCutNode(ply, depth, beta, allowNull) {
    if (ply <= 0) {
        return QSearch(beta - 1, beta, 0);
    }

    if ((g_nodeCount & 127) == 127) {
        if ((new Date()).getTime() - g_startTime > g_timeout) {
            // Time cutoff
            g_searchValid = false;
            return beta - 1;
        }
    }

    g_nodeCount++;

    if (IsRepDraw())
        return 0;

    // Mate distance pruning
    if (minEval + depth >= beta)
       return beta;

    if (maxEval - (depth + 1) < beta)
	return beta - 1;

    var hashMove = null;
    var hashNode = g_hashTable[g_hashKeyLow & g_hashMask];
    if (hashNode != null && hashNode.lock == g_hashKeyHigh) {
        hashMove = hashNode.bestMove;
        if (hashNode.hashDepth >= ply) {
            var hashValue = hashNode.value;

            // Fixup mate scores
            if (hashValue >= maxMateBuffer)
		hashValue -= depth;
            else if (hashValue <= minMateBuffer)
                hashValue += depth;

            if (hashNode.flags == hashflagExact)
                return hashValue;
            if (hashNode.flags == hashflagAlpha && hashValue < beta)
                return hashValue;
            if (hashNode.flags == hashflagBeta && hashValue >= beta)
                return hashValue;
        }
    }

    // TODO - positional gain?

    if (!g_inCheck &&
        allowNull &&
        beta > minMateBuffer && 
        beta < maxMateBuffer) {
        // Try some razoring
        if (hashMove == null &&
            ply < 4) {
            var razorMargin = 2500 + 200 * ply;
            if (g_baseEval < beta - razorMargin) {
                var razorBeta = beta - razorMargin;
                var v = QSearch(razorBeta - 1, razorBeta, 0);
                if (v < razorBeta)
                    return v;
            }
        }
        
        // TODO - static null move

        // Null move
        if (ply > 1 &&
            g_baseEval >= beta - (ply >= 4 ? 2500 : 0) &&
            // Disable null move if potential zugzwang (no big pieces)
            (g_pieceCount[pieceBishop | g_toMove] != 0 ||
             g_pieceCount[pieceKnight | g_toMove] != 0 ||
             g_pieceCount[pieceRook | g_toMove] != 0 ||
             g_pieceCount[pieceQueen | g_toMove] != 0)) {
            var r = 3 + (ply >= 5 ? 1 : ply / 4);
            if (g_baseEval - beta > 1500) r++;

	        g_toMove = 8 - g_toMove;
	        g_baseEval = -g_baseEval;
	        g_hashKeyLow ^= g_zobristBlackLow;
	        g_hashKeyHigh ^= g_zobristBlackHigh;
			
	        var value = -AllCutNode(ply - r, depth + 1, -(beta - 1), false);

	        g_hashKeyLow ^= g_zobristBlackLow;
	        g_hashKeyHigh ^= g_zobristBlackHigh;
	        g_toMove = 8 - g_toMove;
	        g_baseEval = -g_baseEval;

            if (value >= beta)
	            return beta;
        }
    }

    var moveMade = false;
    var realEval = minEval - 1;
    var inCheck = g_inCheck;

    var movePicker = new MovePicker(hashMove, depth, g_killers[depth][0], g_killers[depth][1]);

    for (;;) {
        var currentMove = movePicker.nextMove();
        if (currentMove == 0) {
            break;
        }

        var plyToSearch = ply - 1;

        if (!MakeMove(currentMove)) {
            continue;
        }

        var value;
        var doFullSearch = true;

        if (g_inCheck) {
            // Check extensions
            plyToSearch++;
        } else {
            var reduced = plyToSearch - (movePicker.atMove > 14 ? 2 : 1);

            // Futility pruning
/*            if (movePicker.stage == 5 && !inCheck) {
                if (movePicker.atMove >= (15 + (1 << (5 * ply) >> 2)) &&
                    realEval > minMateBuffer) {
                    UnmakeMove(currentMove);
                    continue;
                }

                if (ply < 7) {
                    var reducedPly = reduced <= 0 ? 0 : reduced;
                    var futilityValue = -g_baseEval + (900 * (reducedPly + 2)) - (movePicker.atMove * 10);
                    if (futilityValue < beta) {
                        if (futilityValue > realEval) {
                            realEval = futilityValue;
                        }
                        UnmakeMove(currentMove);
                        continue;
                    }
                }
            }*/

            // Late move reductions
            if (movePicker.stage == 5 && movePicker.atMove > 5 && ply >= 3) {
                value = -AllCutNode(reduced, depth + 1, -(beta - 1), true);
                doFullSearch = (value >= beta);
            }
        }

        if (doFullSearch) {
            value = -AllCutNode(plyToSearch, depth + 1, -(beta  - 1), true);
        }

        moveMade = true;

        UnmakeMove(currentMove);

        if (!g_searchValid) {
            return beta - 1;
        }

        if (value > realEval) {
            if (value >= beta) {
				var histTo = (currentMove >> 8) & 0xFF;
				if (g_board[histTo] == 0) {
				    var histPiece = g_board[currentMove & 0xFF] & 0xF;
				    historyTable[histPiece][histTo] += ply * ply;
				    if (historyTable[histPiece][histTo] > 32767) {
				        historyTable[histPiece][histTo] >>= 1;
				    }

				    if (g_killers[depth][0] != currentMove) {
				        g_killers[depth][1] = g_killers[depth][0];
				        g_killers[depth][0] = currentMove;
				    }
				}

                StoreHash(value, hashflagBeta, ply, currentMove, depth);
                return value;
            }

            realEval = value;
            hashMove = currentMove;
        }
    }

    if (!moveMade) {
        // If we have no valid moves it's either stalemate or checkmate
        if (g_inCheck)
            // Checkmate.
            return minEval + depth;
        else 
            // Stalemate
            return 0;
    }

    StoreHash(realEval, hashflagAlpha, ply, hashMove, depth);
    
    return realEval;
}

function AlphaBeta(ply, depth, alpha, beta) {
    if (ply <= 0) {
        return QSearch(alpha, beta, 0);
    }

    g_nodeCount++;

    if (depth > 0 && IsRepDraw())
        return 0;

    // Mate distance pruning
    var oldAlpha = alpha;
    alpha = alpha < minEval + depth ? alpha : minEval + depth;
    beta = beta > maxEval - (depth + 1) ? beta : maxEval - (depth + 1);
    if (alpha >= beta)
       return alpha;

    var hashMove = null;
    var hashFlag = hashflagAlpha;
    var hashNode = g_hashTable[g_hashKeyLow & g_hashMask];
    if (hashNode != null && hashNode.lock == g_hashKeyHigh) {
        hashMove = hashNode.bestMove;
    }
    
    var inCheck = g_inCheck;

    var moveMade = false;
    var realEval = minEval;

    var movePicker = new MovePicker(hashMove, depth, g_killers[depth][0], g_killers[depth][1]);

    for (;;) {
        var currentMove = movePicker.nextMove();
        if (currentMove == 0) {
            break;
        }

        var plyToSearch = ply - 1;

        if (!MakeMove(currentMove)) {
            continue;
        }

        if (g_inCheck) {
            // Check extensions
            plyToSearch++;
        }

        var value;
        if (moveMade) {
            value = -AllCutNode(plyToSearch, depth + 1, -alpha, true);
            if (value > alpha) {
                value = -AlphaBeta(plyToSearch, depth + 1, -beta, -alpha);
            }
        } else {
            value = -AlphaBeta(plyToSearch, depth + 1, -beta, -alpha);
        }

        moveMade = true;

        UnmakeMove(currentMove);

        if (!g_searchValid) {
            return alpha;
        }

        if (value > realEval) {
            if (value >= beta) {
                var histTo = (currentMove >> 8) & 0xFF;
                if (g_board[histTo] == 0) {
                    var histPiece = g_board[currentMove & 0xFF] & 0xF;
                    historyTable[histPiece][histTo] += ply * ply;
                    if (historyTable[histPiece][histTo] > 32767) {
                        historyTable[histPiece][histTo] >>= 1;
                    }

                    if (g_killers[depth][0] != currentMove) {
                        g_killers[depth][1] = g_killers[depth][0];
                        g_killers[depth][0] = currentMove;
                    }
                }

                StoreHash(value, hashflagBeta, ply, currentMove, depth);
                return value;
            }

            if (value > oldAlpha) {
                hashFlag = hashflagExact;
                alpha = value;
            }

            realEval = value;
            hashMove = currentMove;
        }
    }

    if (!moveMade) {
        // If we have no valid moves it's either stalemate or checkmate
        if (inCheck) 
            // Checkmate.
            return minEval + depth;
        else 
            // Stalemate
            return 0;
    }

    StoreHash(realEval, hashFlag, ply, hashMove, depth);
    
    return realEval;
}

// 
// Board code
//

// This somewhat funky scheme means that a piece is indexed by it's lower 4 bits when accessing in arrays.  The fifth bit (black bit)
// is used to allow quick edge testing on the board.
var colorBlack = 0x10;
var colorWhite = 0x08;

var pieceEmpty = 0x00;
var piecePawn = 0x01;
var pieceKnight = 0x02;
var pieceBishop = 0x03;
var pieceRook = 0x04;
var pieceQueen = 0x05;
var pieceKing = 0x06;

var g_vectorDelta = new Array(256);

var g_bishopDeltas = [-15, -17, 15, 17];
var g_knightDeltas = [31, 33, 14, -14, -31, -33, 18, -18];
var g_rookDeltas = [-1, +1, -16, +16];
var g_queenDeltas = [-1, +1, -15, +15, -17, +17, -16, +16];

var g_castleRightsMask = [
0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
0, 0, 0, 0, 7,15,15,15, 3,15,15,11, 0, 0, 0, 0,
0, 0, 0, 0,15,15,15,15,15,15,15,15, 0, 0, 0, 0,
0, 0, 0, 0,15,15,15,15,15,15,15,15, 0, 0, 0, 0,
0, 0, 0, 0,15,15,15,15,15,15,15,15, 0, 0, 0, 0,
0, 0, 0, 0,15,15,15,15,15,15,15,15, 0, 0, 0, 0,
0, 0, 0, 0,15,15,15,15,15,15,15,15, 0, 0, 0, 0,
0, 0, 0, 0,15,15,15,15,15,15,15,15, 0, 0, 0, 0,
0, 0, 0, 0,13,15,15,15,12,15,15,14, 0, 0, 0, 0,
0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

var moveflagEPC = 0x2 << 16;
var moveflagCastleKing = 0x4 << 16;
var moveflagCastleQueen = 0x8 << 16;
var moveflagPromotion = 0x10 << 16;
var moveflagPromoteKnight = 0x20 << 16;
var moveflagPromoteQueen = 0x40 << 16;
var moveflagPromoteBishop = 0x80 << 16;

function MT() {
 	var N = 624;
	var M = 397;
	var MAG01 = [0x0, 0x9908b0df];
    
    this.mt = new Array(N);
    this.mti = N + 1;

    this.setSeed = function()
	{
		var a = arguments;
		switch (a.length) {
		case 1:
			if (a[0].constructor === Number) {
				this.mt[0]= a[0];
				for (var i = 1; i < N; ++i) {
					var s = this.mt[i - 1] ^ (this.mt[i - 1] >>> 30);
					this.mt[i] = ((1812433253 * ((s & 0xffff0000) >>> 16))
							<< 16)
						+ 1812433253 * (s & 0x0000ffff)
						+ i;
				}
				this.mti = N;
				return;
			}

			this.setSeed(19650218);

			var l = a[0].length;
			var i = 1;
			var j = 0;

			for (var k = N > l ? N : l; k != 0; --k) {
				var s = this.mt[i - 1] ^ (this.mt[i - 1] >>> 30)
				this.mt[i] = (this.mt[i]
						^ (((1664525 * ((s & 0xffff0000) >>> 16)) << 16)
							+ 1664525 * (s & 0x0000ffff)))
					+ a[0][j]
					+ j;
				if (++i >= N) {
					this.mt[0] = this.mt[N - 1];
					i = 1;
				}
				if (++j >= l) {
					j = 0;
				}
			}

			for (var k = N - 1; k != 0; --k) {
				var s = this.mt[i - 1] ^ (this.mt[i - 1] >>> 30);
				this.mt[i] = (this.mt[i]
						^ (((1566083941 * ((s & 0xffff0000) >>> 16)) << 16)
							+ 1566083941 * (s & 0x0000ffff)))
					- i;
				if (++i >= N) {
					this.mt[0] = this.mt[N-1];
					i = 1;
				}
			}

			this.mt[0] = 0x80000000;
			return;
		default:
			var seeds = new Array();
			for (var i = 0; i < a.length; ++i) {
				seeds.push(a[i]);
			}
			this.setSeed(seeds);
			return;
		}
	}

    this.setSeed(0x1BADF00D);

    this.next = function (bits)
	{
		if (this.mti >= N) {
			var x = 0;

			for (var k = 0; k < N - M; ++k) {
				x = (this.mt[k] & 0x80000000) | (this.mt[k + 1] & 0x7fffffff);
				this.mt[k] = this.mt[k + M] ^ (x >>> 1) ^ MAG01[x & 0x1];
			}
			for (var k = N - M; k < N - 1; ++k) {
				x = (this.mt[k] & 0x80000000) | (this.mt[k + 1] & 0x7fffffff);
				this.mt[k] = this.mt[k + (M - N)] ^ (x >>> 1) ^ MAG01[x & 0x1];
			}
			x = (this.mt[N - 1] & 0x80000000) | (this.mt[0] & 0x7fffffff);
			this.mt[N - 1] = this.mt[M - 1] ^ (x >>> 1) ^ MAG01[x & 0x1];

			this.mti = 0;
		}

		var y = this.mt[this.mti++];
		y ^= y >>> 11;
		y ^= (y << 7) & 0x9d2c5680;
		y ^= (y << 15) & 0xefc60000;
		y ^= y >>> 18;
		return (y >>> (32 - bits)) & 0xFFFFFFFF;
	}
}

// Position variables
var g_board = new Array(256); // Sentinel 0x80, pieces are in low 4 bits, 0x8 for color, 0x7 bits for piece type
var g_toMove; // side to move, 0 or 8, 0 = black, 8 = white
var g_castleRights; // bitmask representing castling rights, 1 = wk, 2 = wq, 4 = bk, 8 = bq
var g_enPassentSquare;
var g_baseEval;
var g_hashKeyLow, g_hashKeyHigh;
var g_inCheck;

// Utility variables
var g_moveCount = 0;
var g_moveUndoStack = new Array();

var g_move50 = 0;
var g_repMoveStack = new Array();

var g_hashSize = 1 << 22;
var g_hashMask = g_hashSize - 1;
var g_hashTable;

var g_killers;
var historyTable = new Array(32);

var g_zobristLow;
var g_zobristHigh;
var g_zobristBlackLow;
var g_zobristBlackHigh;

// Evaulation variables
var g_mobUnit;

var hashflagAlpha = 1;
var hashflagBeta = 2;
var hashflagExact = 3;

function HashEntry(lock, value, flags, hashDepth, bestMove, globalPly) {
    this.lock = lock;
    this.value = value;
    this.flags = flags;
    this.hashDepth = hashDepth;
    this.bestMove = bestMove;
}

function MakeSquare(row, column) {
    return ((row + 2) << 4) | (column + 4);
}

function MakeTable(table) {
    var result = new Array(256);
    for (var i = 0; i < 256; i++) {
        result[i] = 0;
    }
    for (var row = 0; row < 8; row++) {
        for (var col = 0; col < 8; col++) {
            result[MakeSquare(row, col)] = table[row * 8 + col];
        }
    }
    return result;
}

function ResetGame() {
    g_killers = new Array(128);
    for (var i = 0; i < 128; i++) {
        g_killers[i] = [0, 0];
    }

    g_hashTable = new Array(g_hashSize);

    for (var i = 0; i < 32; i++) {
        historyTable[i] = new Array(256);
        for (var j = 0; j < 256; j++)
            historyTable[i][j] = 0;
    }

    var mt = new MT(0x1badf00d);

    g_zobristLow = new Array(256);
    g_zobristHigh = new Array(256);
    for (var i = 0; i < 256; i++) {
        g_zobristLow[i] = new Array(16);
        g_zobristHigh[i] = new Array(16);
        for (var j = 0; j < 16; j++) {
            g_zobristLow[i][j] = mt.next(32);
            g_zobristHigh[i][j] = mt.next(32);
        }
    }
    g_zobristBlackLow = mt.next(32);
    g_zobristBlackHigh = mt.next(32);

    for (var row = 0; row < 8; row++) {
        for (var col = 0; col < 8; col++) {
            var square = MakeSquare(row, col);
            flipTable[square] = MakeSquare(7 - row, col);
        }
    }

    pieceSquareAdj[piecePawn] = MakeTable(pawnAdj);
    pieceSquareAdj[pieceKnight] = MakeTable(knightAdj);
    pieceSquareAdj[pieceBishop] = MakeTable(bishopAdj);
    pieceSquareAdj[pieceRook] = MakeTable(rookAdj);
    pieceSquareAdj[pieceQueen] = MakeTable(emptyAdj);
    pieceSquareAdj[pieceKing] = MakeTable(kingAdj);

    var pieceDeltas = [[], [], g_knightDeltas, g_bishopDeltas, g_rookDeltas, g_queenDeltas, g_queenDeltas];

    for (var i = 0; i < 256; i++) {
        g_vectorDelta[i] = new Object();
        g_vectorDelta[i].delta = 0;
        g_vectorDelta[i].pieceMask = new Array(2);
        g_vectorDelta[i].pieceMask[0] = 0;
        g_vectorDelta[i].pieceMask[1] = 0;
    }
    
    // Initialize the vector delta table    
    for (var row = 0; row < 0x80; row += 0x10) 
        for (var col = 0; col < 0x8; col++) {
            var square = row | col;
            
            // Pawn moves
            var index = square - (square - 17) + 128;
            g_vectorDelta[index].pieceMask[colorWhite >> 3] |= (1 << piecePawn);
            index = square - (square - 15) + 128;
            g_vectorDelta[index].pieceMask[colorWhite >> 3] |= (1 << piecePawn);
            
            index = square - (square + 17) + 128;
            g_vectorDelta[index].pieceMask[0] |= (1 << piecePawn);
            index = square - (square + 15) + 128;
            g_vectorDelta[index].pieceMask[0] |= (1 << piecePawn);
            
            for (var i = pieceKnight; i <= pieceKing; i++) {
                for (var dir = 0; dir < pieceDeltas[i].length; dir++) {
                    var target = square + pieceDeltas[i][dir];
                    while (!(target & 0x88)) {
                        index = square - target + 128;
                        
                        g_vectorDelta[index].pieceMask[colorWhite >> 3] |= (1 << i);
                        g_vectorDelta[index].pieceMask[0] |= (1 << i);
                        
                        var flip = -1;
                        if (square < target) 
                            flip = 1;
                        
                        if ((square & 0xF0) == (target & 0xF0)) {
                            // On the same row
                            g_vectorDelta[index].delta = flip * 1;
                        } else if ((square & 0x0F) == (target & 0x0F)) {
                            // On the same column
                            g_vectorDelta[index].delta = flip * 16;
                        } else if ((square % 15) == (target % 15)) {
                            g_vectorDelta[index].delta = flip * 15;
                        } else if ((square % 17) == (target % 17)) {
                            g_vectorDelta[index].delta = flip * 17;
                        }

                        if (i == pieceKnight) {
                            g_vectorDelta[index].delta = pieceDeltas[i][dir];
                            break;
                        }

                        if (i == pieceKing)
                            break;

                        target += pieceDeltas[i][dir];
                    }
                }
            }
        }

    InitializeEval();

    var FENstart = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";

    InitializeFromFen(FENstart);
}




function InitializeEval() {
    g_mobUnit = new Array(2);
    for (var i = 0; i < 2; i++) {
        g_mobUnit[i] = new Array();
        var enemy = i == 0 ? 0x10 : 8;
        var friend = i == 0 ? 8 : 0x10;
        g_mobUnit[i][0] = 1;
        g_mobUnit[i][0x80] = 0;
        g_mobUnit[i][enemy | piecePawn] = 1;
        g_mobUnit[i][enemy | pieceBishop] = 2;
        g_mobUnit[i][enemy | pieceKnight] = 2;
        g_mobUnit[i][enemy | pieceRook] = 4;
        g_mobUnit[i][enemy | pieceQueen] = 6;
        g_mobUnit[i][enemy | pieceKing] = 6;
        g_mobUnit[i][friend | piecePawn] = 0;
        g_mobUnit[i][friend | pieceBishop] = 0;
        g_mobUnit[i][friend | pieceKnight] = 0;
        g_mobUnit[i][friend | pieceRook] = 0;
        g_mobUnit[i][friend | pieceQueen] = 0;
        g_mobUnit[i][friend | pieceKing] = 0;
    }
}

function SetHash() {
    var result = new Object();
    result.hashKeyLow = 0;
    result.hashKeyHigh = 0;

    for (var i = 0; i < 256; i++) {
        var piece = g_board[i];
        if (piece & 0x18) {
            result.hashKeyLow ^= g_zobristLow[i][piece & 0xF]
            result.hashKeyHigh ^= g_zobristHigh[i][piece & 0xF]
        }
    }

    if (!g_toMove) {
        result.hashKeyLow ^= g_zobristBlackLow;
        result.hashKeyHigh ^= g_zobristBlackHigh;
    }

    return result;
}

function InitializeFromFen(fen) {
    var chunks = fen.split(' ');
    
    for (var i = 0; i < 256; i++) 
        g_board[i] = 0x80;
    
    var row = 0;
    var col = 0;
    
    var pieces = chunks[0];
    for (var i = 0; i < pieces.length; i++) {
        var c = pieces.charAt(i);
        
        if (c == '/') {
            row++;
            col = 0;
        }
        else {
            if (c >= '0' && c <= '9') {
                for (var j = 0; j < parseInt(c); j++) {
                    g_board[MakeSquare(row, col)] = 0;
                    col++;
                }
            }
            else {
                var isBlack = c >= 'a' && c <= 'z';
                var piece = isBlack ? colorBlack : colorWhite;
                if (!isBlack) 
                    c = pieces.toLowerCase().charAt(i);
                switch (c) {
                    case 'p':
                        piece |= piecePawn;
                        break;
                    case 'b':
                        piece |= pieceBishop;
                        break;
                    case 'n':
                        piece |= pieceKnight;
                        break;
                    case 'r':
                        piece |= pieceRook;
                        break;
                    case 'q':
                        piece |= pieceQueen;
                        break;
                    case 'k':
                        piece |= pieceKing;
                        break;
                }
                
                g_board[MakeSquare(row, col)] = piece;
                col++;
            }
        }
    }
    
    InitializePieceList();
    
    g_toMove = chunks[1].charAt(0) == 'w' ? colorWhite : 0;
    var them = 8 - g_toMove;
    
    g_castleRights = 0;
    if (chunks[2].indexOf('K') != -1) { 
        if (g_board[MakeSquare(7, 4)] != (pieceKing | colorWhite) ||
            g_board[MakeSquare(7, 7)] != (pieceRook | colorWhite)) {
            return 'Invalid FEN: White kingside castling not allowed';
        }
        g_castleRights |= 1;
    }
    if (chunks[2].indexOf('Q') != -1) {
        if (g_board[MakeSquare(7, 4)] != (pieceKing | colorWhite) ||
            g_board[MakeSquare(7, 0)] != (pieceRook | colorWhite)) {
            return 'Invalid FEN: White queenside castling not allowed';
        }
        g_castleRights |= 2;
    }
    if (chunks[2].indexOf('k') != -1) {
        if (g_board[MakeSquare(0, 4)] != (pieceKing | colorBlack) ||
            g_board[MakeSquare(0, 7)] != (pieceRook | colorBlack)) {
            return 'Invalid FEN: Black kingside castling not allowed';
        }
        g_castleRights |= 4;
    }
    if (chunks[2].indexOf('q') != -1) {
        if (g_board[MakeSquare(0, 4)] != (pieceKing | colorBlack) ||
            g_board[MakeSquare(0, 0)] != (pieceRook | colorBlack)) {
            return 'Invalid FEN: Black queenside castling not allowed';
        }
        g_castleRights |= 8;
    }
    
    g_enPassentSquare = -1;
    if (chunks[3].indexOf('-') == -1) {
	var col = chunks[3].charAt(0).charCodeAt() - 'a'.charCodeAt();
	var row = 8 - (chunks[3].charAt(1).charCodeAt() - '0'.charCodeAt());
	g_enPassentSquare = MakeSquare(row, col);
    }

    var hashResult = SetHash();
    g_hashKeyLow = hashResult.hashKeyLow;
    g_hashKeyHigh = hashResult.hashKeyHigh;

    g_baseEval = 0;
    for (var i = 0; i < 256; i++) {
        if (g_board[i] & colorWhite) {
            g_baseEval += pieceSquareAdj[g_board[i] & 0x7][i];
            g_baseEval += materialTable[g_board[i] & 0x7];
        } else if (g_board[i] & colorBlack) {
            g_baseEval -= pieceSquareAdj[g_board[i] & 0x7][flipTable[i]];
            g_baseEval -= materialTable[g_board[i] & 0x7];
        }
    }
    if (!g_toMove) g_baseEval = -g_baseEval;

    g_move50 = 0;
    g_inCheck = IsSquareAttackable(g_pieceList[(g_toMove | pieceKing) << 4], them);

    // Check for king capture (invalid FEN)
    if (IsSquareAttackable(g_pieceList[(them | pieceKing) << 4], g_toMove)) {
        return 'Invalid FEN: Can capture king';
    }

    // Checkmate/stalemate
    if (GenerateValidMoves().length == 0) {
        return g_inCheck ? 'Checkmate' : 'Stalemate';
    } 

    return '';
}

var g_pieceIndex = new Array(256);
var g_pieceList = new Array(2 * 8 * 16);
var g_pieceCount = new Array(2 * 8);

function InitializePieceList() {
    for (var i = 0; i < 16; i++) {
        g_pieceCount[i] = 0;
        for (var j = 0; j < 16; j++) {
            // 0 is used as the terminator for piece lists
            g_pieceList[(i << 4) | j] = 0;
        }
    }

    for (var i = 0; i < 256; i++) {
        g_pieceIndex[i] = 0;
        if (g_board[i] & (colorWhite | colorBlack)) {
			var piece = g_board[i] & 0xF;

			g_pieceList[(piece << 4) | g_pieceCount[piece]] = i;
			g_pieceIndex[i] = g_pieceCount[piece];
			g_pieceCount[piece]++;
        }
    }
}

var NULLMOVE = 8388608; // 2^23

function MakeMove(move){

 if (move == NULLMOVE) { makenullmove(); return; } 

    var me = g_toMove >> 3;
    var otherColor = 8 - g_toMove; 
    
    var flags = move & 0xFF0000;
    var to = (move >> 8) & 0xFF;
    var from = move & 0xFF;
    var captured = g_board[to];
    var piece = g_board[from];
    var epcEnd = to;

    if (flags & moveflagEPC) {
        epcEnd = me ? (to + 0x10) : (to - 0x10);
        captured = g_board[epcEnd];
        g_board[epcEnd] = pieceEmpty;
    }

    g_moveUndoStack[g_moveCount] = new UndoHistory(g_enPassentSquare, g_castleRights, g_inCheck, g_baseEval, g_hashKeyLow, g_hashKeyHigh, g_move50, captured);
    g_moveCount++;

    g_enPassentSquare = -1;

    if (flags) {
        if (flags & moveflagCastleKing) {
            if (IsSquareAttackable(from + 1, otherColor) ||
            	IsSquareAttackable(from + 2, otherColor)) {
                g_moveCount--;
                return false;
            }
            
            var rook = g_board[to + 1];
            
            g_hashKeyLow ^= g_zobristLow[to + 1][rook & 0xF];
            g_hashKeyHigh ^= g_zobristHigh[to + 1][rook & 0xF];
            g_hashKeyLow ^= g_zobristLow[to - 1][rook & 0xF];
            g_hashKeyHigh ^= g_zobristHigh[to - 1][rook & 0xF];
            
            g_board[to - 1] = rook;
            g_board[to + 1] = pieceEmpty;
            
            g_baseEval -= pieceSquareAdj[rook & 0x7][me == 0 ? flipTable[to + 1] : (to + 1)];
            g_baseEval += pieceSquareAdj[rook & 0x7][me == 0 ? flipTable[to - 1] : (to - 1)];

            var rookIndex = g_pieceIndex[to + 1];
            g_pieceIndex[to - 1] = rookIndex;
            g_pieceList[((rook & 0xF) << 4) | rookIndex] = to - 1;
        } else if (flags & moveflagCastleQueen) {
            if (IsSquareAttackable(from - 1, otherColor) ||
            	IsSquareAttackable(from - 2, otherColor)) {
                g_moveCount--;
                return false;
            }
            
            var rook = g_board[to - 2];

            g_hashKeyLow ^= g_zobristLow[to -2][rook & 0xF];
            g_hashKeyHigh ^= g_zobristHigh[to - 2][rook & 0xF];
            g_hashKeyLow ^= g_zobristLow[to + 1][rook & 0xF];
            g_hashKeyHigh ^= g_zobristHigh[to + 1][rook & 0xF];
            
            g_board[to + 1] = rook;
            g_board[to - 2] = pieceEmpty;
            
            g_baseEval -= pieceSquareAdj[rook & 0x7][me == 0 ? flipTable[to - 2] : (to - 2)];
            g_baseEval += pieceSquareAdj[rook & 0x7][me == 0 ? flipTable[to + 1] : (to + 1)];

            var rookIndex = g_pieceIndex[to - 2];
            g_pieceIndex[to + 1] = rookIndex;
            g_pieceList[((rook & 0xF) << 4) | rookIndex] = to + 1;
        }
    }

    if (captured) {
        // Remove our piece from the piece list
        var capturedType = captured & 0xF;
        g_pieceCount[capturedType]--;
        var lastPieceSquare = g_pieceList[(capturedType << 4) | g_pieceCount[capturedType]];
        g_pieceIndex[lastPieceSquare] = g_pieceIndex[epcEnd];
        g_pieceList[(capturedType << 4) | g_pieceIndex[lastPieceSquare]] = lastPieceSquare;
        g_pieceList[(capturedType << 4) | g_pieceCount[capturedType]] = 0;

        g_baseEval += materialTable[captured & 0x7];
        g_baseEval += pieceSquareAdj[captured & 0x7][me ? flipTable[epcEnd] : epcEnd];

        g_hashKeyLow ^= g_zobristLow[epcEnd][capturedType];
        g_hashKeyHigh ^= g_zobristHigh[epcEnd][capturedType];
        g_move50 = 0;
    } else if ((piece & 0x7) == piecePawn) {
        var diff = to - from;
        if (diff < 0) diff = -diff;
        if (diff > 16) {
            g_enPassentSquare = me ? (to + 0x10) : (to - 0x10);
        }
        g_move50 = 0;
    }

    g_hashKeyLow ^= g_zobristLow[from][piece & 0xF];
    g_hashKeyHigh ^= g_zobristHigh[from][piece & 0xF];
    g_hashKeyLow ^= g_zobristLow[to][piece & 0xF];
    g_hashKeyHigh ^= g_zobristHigh[to][piece & 0xF];
    g_hashKeyLow ^= g_zobristBlackLow;
    g_hashKeyHigh ^= g_zobristBlackHigh;
    
    g_castleRights &= g_castleRightsMask[from] & g_castleRightsMask[to];

    g_baseEval -= pieceSquareAdj[piece & 0x7][me == 0 ? flipTable[from] : from];
    
    // Move our piece in the piece list
    g_pieceIndex[to] = g_pieceIndex[from];
    g_pieceList[((piece & 0xF) << 4) | g_pieceIndex[to]] = to;

    if (flags & moveflagPromotion) {
        var newPiece = piece & (~0x7);
        if (flags & moveflagPromoteKnight) 
            newPiece |= pieceKnight;
        else if (flags & moveflagPromoteQueen) 
            newPiece |= pieceQueen;
        else if (flags & moveflagPromoteBishop) 
            newPiece |= pieceBishop;
        else 
            newPiece |= pieceRook;

        g_hashKeyLow ^= g_zobristLow[to][piece & 0xF];
        g_hashKeyHigh ^= g_zobristHigh[to][piece & 0xF];
        g_board[to] = newPiece;
        g_hashKeyLow ^= g_zobristLow[to][newPiece & 0xF];
        g_hashKeyHigh ^= g_zobristHigh[to][newPiece & 0xF];
        
        g_baseEval += pieceSquareAdj[newPiece & 0x7][me == 0 ? flipTable[to] : to];
        g_baseEval -= materialTable[piecePawn];
        g_baseEval += materialTable[newPiece & 0x7];

        var pawnType = piece & 0xF;
        var promoteType = newPiece & 0xF;

        g_pieceCount[pawnType]--;

        var lastPawnSquare = g_pieceList[(pawnType << 4) | g_pieceCount[pawnType]];
        g_pieceIndex[lastPawnSquare] = g_pieceIndex[to];
        g_pieceList[(pawnType << 4) | g_pieceIndex[lastPawnSquare]] = lastPawnSquare;
        g_pieceList[(pawnType << 4) | g_pieceCount[pawnType]] = 0;
        g_pieceIndex[to] = g_pieceCount[promoteType];
        g_pieceList[(promoteType << 4) | g_pieceIndex[to]] = to;
        g_pieceCount[promoteType]++;
    } else {
        g_board[to] = g_board[from];
        
        g_baseEval += pieceSquareAdj[piece & 0x7][me == 0 ? flipTable[to] : to];
    }
    g_board[from] = pieceEmpty;

    g_toMove = otherColor;
    g_baseEval = -g_baseEval;
    
    if ((piece & 0x7) == pieceKing || g_inCheck) {
        if (IsSquareAttackable(g_pieceList[(pieceKing | (8 - g_toMove)) << 4], otherColor)) {
            UnmakeMove(move);
            return false;
        }
    } else {
        var kingPos = g_pieceList[(pieceKing | (8 - g_toMove)) << 4];
        
        if (ExposesCheck(from, kingPos)) {
            UnmakeMove(move);
            return false;
        }
        
        if (epcEnd != to) {
            if (ExposesCheck(epcEnd, kingPos)) {
                UnmakeMove(move);
                return false;
            }
        }
    }
    
    g_inCheck = false;
    
    if (flags <= moveflagEPC) {
        var theirKingPos = g_pieceList[(pieceKing | g_toMove) << 4];
        
        // First check if the piece we moved can attack the enemy king
        g_inCheck = IsSquareAttackableFrom(theirKingPos, to);
        
        if (!g_inCheck) {
            // Now check if the square we moved from exposes check on the enemy king
            g_inCheck = ExposesCheck(from, theirKingPos);
            
            if (!g_inCheck) {
                // Finally, ep. capture can cause another square to be exposed
                if (epcEnd != to) {
                    g_inCheck = ExposesCheck(epcEnd, theirKingPos);
                }
            }
        }
    }
    else {
        // Castle or promotion, slow check
        g_inCheck = IsSquareAttackable(g_pieceList[(pieceKing | g_toMove) << 4], 8 - g_toMove);
    }

    g_repMoveStack[g_moveCount - 1] = g_hashKeyLow;
    g_move50++;

    return true;
}

function UnmakeMove(move){

  if (move == NULLMOVE) { makenullmove(); return; }

    g_toMove = 8 - g_toMove;
    g_baseEval = -g_baseEval;
    
    g_moveCount--;
    g_enPassentSquare = g_moveUndoStack[g_moveCount].ep;
    g_castleRights = g_moveUndoStack[g_moveCount].castleRights;
    g_inCheck = g_moveUndoStack[g_moveCount].inCheck;
    g_baseEval = g_moveUndoStack[g_moveCount].baseEval;
    g_hashKeyLow = g_moveUndoStack[g_moveCount].hashKeyLow;
    g_hashKeyHigh = g_moveUndoStack[g_moveCount].hashKeyHigh;
    g_move50 = g_moveUndoStack[g_moveCount].move50;
    
    var otherColor = 8 - g_toMove;
    var me = g_toMove >> 3;
    var them = otherColor >> 3;
    
    var flags = move & 0xFF0000;
    var captured = g_moveUndoStack[g_moveCount].captured;
    var to = (move >> 8) & 0xFF;
    var from = move & 0xFF;
    
    var piece = g_board[to];
    
    if (flags) {
        if (flags & moveflagCastleKing) {
            var rook = g_board[to - 1];
            g_board[to + 1] = rook;
            g_board[to - 1] = pieceEmpty;
			
            var rookIndex = g_pieceIndex[to - 1];
            g_pieceIndex[to + 1] = rookIndex;
            g_pieceList[((rook & 0xF) << 4) | rookIndex] = to + 1;
        }
        else if (flags & moveflagCastleQueen) {
            var rook = g_board[to + 1];
            g_board[to - 2] = rook;
            g_board[to + 1] = pieceEmpty;
			
            var rookIndex = g_pieceIndex[to + 1];
            g_pieceIndex[to - 2] = rookIndex;
            g_pieceList[((rook & 0xF) << 4) | rookIndex] = to - 2;
        }
    }
    
    if (flags & moveflagPromotion) {
        piece = (g_board[to] & (~0x7)) | piecePawn;
        g_board[from] = piece;

        var pawnType = g_board[from] & 0xF;
        var promoteType = g_board[to] & 0xF;

        g_pieceCount[promoteType]--;

        var lastPromoteSquare = g_pieceList[(promoteType << 4) | g_pieceCount[promoteType]];
        g_pieceIndex[lastPromoteSquare] = g_pieceIndex[to];
        g_pieceList[(promoteType << 4) | g_pieceIndex[lastPromoteSquare]] = lastPromoteSquare;
        g_pieceList[(promoteType << 4) | g_pieceCount[promoteType]] = 0;
        g_pieceIndex[to] = g_pieceCount[pawnType];
        g_pieceList[(pawnType << 4) | g_pieceIndex[to]] = to;
        g_pieceCount[pawnType]++;
    }
    else {
        g_board[from] = g_board[to];
    }

    var epcEnd = to;
    if (flags & moveflagEPC) {
        if (g_toMove == colorWhite) 
            epcEnd = to + 0x10;
        else 
            epcEnd = to - 0x10;
        g_board[to] = pieceEmpty;
    }
    
    g_board[epcEnd] = captured;

	// Move our piece in the piece list
    g_pieceIndex[from] = g_pieceIndex[to];
    g_pieceList[((piece & 0xF) << 4) | g_pieceIndex[from]] = from;

    if (captured) {
		// Restore our piece to the piece list
        var captureType = captured & 0xF;
        g_pieceIndex[epcEnd] = g_pieceCount[captureType];
        g_pieceList[(captureType << 4) | g_pieceCount[captureType]] = epcEnd;
        g_pieceCount[captureType]++;
    }
}

function ExposesCheck(from, kingPos){
    var index = kingPos - from + 128;
    // If a queen can't reach it, nobody can!
    if ((g_vectorDelta[index].pieceMask[0] & (1 << (pieceQueen))) != 0) {
        var delta = g_vectorDelta[index].delta;
        var pos = kingPos + delta;
        while (g_board[pos] == 0) pos += delta;
        
        var piece = g_board[pos];
        if (((piece & (g_board[kingPos] ^ 0x18)) & 0x18) == 0)
            return false;

        // Now see if the piece can actually attack the king
        var backwardIndex = pos - kingPos + 128;
        return (g_vectorDelta[backwardIndex].pieceMask[(piece >> 3) & 1] & (1 << (piece & 0x7))) != 0;
    }
    return false;
}

function IsSquareOnPieceLine(target, from) {
    var index = from - target + 128;
    var piece = g_board[from];
    return (g_vectorDelta[index].pieceMask[(piece >> 3) & 1] & (1 << (piece & 0x7))) ? true : false;
}

function IsSquareAttackableFrom(target, from){
    var index = from - target + 128;
    var piece = g_board[from];
    if (g_vectorDelta[index].pieceMask[(piece >> 3) & 1] & (1 << (piece & 0x7))) {
        // Yes, this square is pseudo-attackable.  Now, check for real attack
		var inc = g_vectorDelta[index].delta;
        do {
			from += inc;
			if (from == target)
				return true;
		} while (g_board[from] == 0);
    }
    
    return false;
}

function IsSquareAttackable(target, color) {
	// Attackable by pawns?
	var inc = color ? -16 : 16;
	var pawn = (color ? colorWhite : colorBlack) | 1;
	if (g_board[target - (inc - 1)] == pawn)
		return true;
	if (g_board[target - (inc + 1)] == pawn)
		return true;
	
	// Attackable by pieces?
	for (var i = 2; i <= 6; i++) {
        var index = (color | i) << 4;
        var square = g_pieceList[index];
		while (square != 0) {
			if (IsSquareAttackableFrom(target, square))
				return true;
			square = g_pieceList[++index];
		}
    }
    return false;
}

function GenerateMove(from, to) {
    return from | (to << 8);
}

function GenerateMove(from, to, flags){
    return from | (to << 8) | flags;
}

function GenerateValidMoves() {
    var moveList = new Array();
    var allMoves = new Array();
    GenerateCaptureMoves(allMoves, null);
    GenerateAllMoves(allMoves);
    
    for (var i = allMoves.length - 1; i >= 0; i--) {
        if (MakeMove(allMoves[i])) {
            moveList[moveList.length] = allMoves[i];
            UnmakeMove(allMoves[i]);
        }
    }
    
    return moveList;
}

function GenerateAllMoves(moveStack) {
    var from, to, piece, pieceIdx;

	// Pawn quiet moves
    pieceIdx = (g_toMove | 1) << 4;
    from = g_pieceList[pieceIdx++];
    while (from != 0) {
        GeneratePawnMoves(moveStack, from);
        from = g_pieceList[pieceIdx++];
    }

    // Knight quiet moves
	pieceIdx = (g_toMove | 2) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from + 31; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 33; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 14; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 14; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 31; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 33; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 18; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 18; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		from = g_pieceList[pieceIdx++];
	}

	// Bishop quiet moves
	pieceIdx = (g_toMove | 3) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from - 15; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to -= 15; }
		to = from - 17; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to -= 17; }
		to = from + 15; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to += 15; }
		to = from + 17; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to += 17; }
		from = g_pieceList[pieceIdx++];
	}

	// Rook quiet moves
	pieceIdx = (g_toMove | 4) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from - 1; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to--; }
		to = from + 1; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to++; }
		to = from + 16; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to += 16; }
		to = from - 16; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to -= 16; }
		from = g_pieceList[pieceIdx++];
	}
	
	// Queen quiet moves
	pieceIdx = (g_toMove | 5) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from - 15; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to -= 15; }
		to = from - 17; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to -= 17; }
		to = from + 15; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to += 15; }
		to = from + 17; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to += 17; }
		to = from - 1; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to--; }
		to = from + 1; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to++; }
		to = from + 16; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to += 16; }
		to = from - 16; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to -= 16; }
		from = g_pieceList[pieceIdx++];
	}
	
	// King quiet moves
	{
		pieceIdx = (g_toMove | 6) << 4;
		from = g_pieceList[pieceIdx];
		to = from - 15; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 17; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 15; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 17; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 1; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 1; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 16; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 16; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		
        if (!g_inCheck) {
            var castleRights = g_castleRights;
            if (!g_toMove) 
                castleRights >>= 2;
            if (castleRights & 1) {
                // Kingside castle
                if (g_board[from + 1] == pieceEmpty && g_board[from + 2] == pieceEmpty) {
                    moveStack[moveStack.length] = GenerateMove(from, from + 0x02, moveflagCastleKing);
                }
            }
            if (castleRights & 2) {
                // Queenside castle
                if (g_board[from - 1] == pieceEmpty && g_board[from - 2] == pieceEmpty && g_board[from - 3] == pieceEmpty) {
                    moveStack[moveStack.length] = GenerateMove(from, from - 0x02, moveflagCastleQueen);
                }
            }
        }
	}
}

function GenerateCaptureMoves(moveStack, moveScores) {
    var from, to, piece, pieceIdx;
    var inc = (g_toMove == 8) ? -16 : 16;
    var enemy = g_toMove == 8 ? 0x10 : 0x8;

    // Pawn captures
    pieceIdx = (g_toMove | 1) << 4;
    from = g_pieceList[pieceIdx++];
    while (from != 0) {
        to = from + inc - 1;
        if (g_board[to] & enemy) {
            MovePawnTo(moveStack, from, to);
        }

        to = from + inc + 1;
        if (g_board[to] & enemy) {
            MovePawnTo(moveStack, from, to);
        }

        from = g_pieceList[pieceIdx++];
    }

    if (g_enPassentSquare != -1) {
        var inc = (g_toMove == colorWhite) ? -16 : 16;
        var pawn = g_toMove | piecePawn;

        var from = g_enPassentSquare - (inc + 1);
        if ((g_board[from] & 0xF) == pawn) {
            moveStack[moveStack.length] = GenerateMove(from, g_enPassentSquare, moveflagEPC);
        }

        from = g_enPassentSquare - (inc - 1);
        if ((g_board[from] & 0xF) == pawn) {
            moveStack[moveStack.length] = GenerateMove(from, g_enPassentSquare, moveflagEPC);
        }
    }

    // Knight captures
	pieceIdx = (g_toMove | 2) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from + 31; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 33; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 14; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 14; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 31; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 33; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 18; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 18; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		from = g_pieceList[pieceIdx++];
	}
	
	// Bishop captures
	pieceIdx = (g_toMove | 3) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from; do { to -= 15; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to -= 17; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to += 15; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to += 17; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		from = g_pieceList[pieceIdx++];
	}
	
	// Rook captures
	pieceIdx = (g_toMove | 4) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from; do { to--; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to++; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to -= 16; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to += 16; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		from = g_pieceList[pieceIdx++];
	}
	
	// Queen captures
	pieceIdx = (g_toMove | 5) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from; do { to -= 15; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to -= 17; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to += 15; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to += 17; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to--; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to++; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to -= 16; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to += 16; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		from = g_pieceList[pieceIdx++];
	}
	
	// King captures
	{
		pieceIdx = (g_toMove | 6) << 4;
		from = g_pieceList[pieceIdx];
		to = from - 15; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 17; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 15; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 17; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 1; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 1; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 16; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 16; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
	}
}

function MovePawnTo(moveStack, start, square) {
	var row = square & 0xF0;
    if ((row == 0x90) || (row == 0x20)) {
        moveStack[moveStack.length] = GenerateMove(start, square, moveflagPromotion | moveflagPromoteQueen);
        moveStack[moveStack.length] = GenerateMove(start, square, moveflagPromotion | moveflagPromoteKnight);
        moveStack[moveStack.length] = GenerateMove(start, square, moveflagPromotion | moveflagPromoteBishop);
        moveStack[moveStack.length] = GenerateMove(start, square, moveflagPromotion);
    }
    else {
        moveStack[moveStack.length] = GenerateMove(start, square, 0);
    }
}

function GeneratePawnMoves(moveStack, from) {
    var piece = g_board[from];
    var color = piece & colorWhite;
    var inc = (color == colorWhite) ? -16 : 16;
    
	// Quiet pawn moves
	var to = from + inc;
	if (g_board[to] == 0) {
		MovePawnTo(moveStack, from, to, pieceEmpty);
		
		// Check if we can do a 2 square jump
		if ((((from & 0xF0) == 0x30) && color != colorWhite) ||
		    (((from & 0xF0) == 0x80) && color == colorWhite)) {
			to += inc;
			if (g_board[to] == 0) {
				moveStack[moveStack.length] = GenerateMove(from, to);
			}				
		}
	}
}

function UndoHistory(ep, castleRights, inCheck, baseEval, hashKeyLow, hashKeyHigh, move50, captured) {
    this.ep = ep;
    this.castleRights = castleRights;
    this.inCheck = inCheck;
    this.baseEval = baseEval;
    this.hashKeyLow = hashKeyLow;
    this.hashKeyHigh = hashKeyHigh;
    this.move50 = move50;
    this.captured = captured;
}

var g_seeValues = [0, 1, 3, 3, 5, 9, 900, 0,
                    0, 1, 3, 3, 5, 9, 900, 0];

function See(move) {
    var from = move & 0xFF;
    var to = (move >> 8) & 0xFF;

    var fromPiece = g_board[from];

    var fromValue = g_seeValues[fromPiece & 0xF];
    var toValue = g_seeValues[g_board[to] & 0xF];

    if (fromValue <= toValue) {
        return true;
    }

    if (move >> 16) {
        // Castles, promotion, ep are always good
        return true;
    }

    var us = (fromPiece & colorWhite) ? colorWhite : 0;
    var them = 8 - us;

    // Pawn attacks 
    // If any opponent pawns can capture back, this capture is probably not worthwhile (as we must be using knight or above).
    var inc = (fromPiece & colorWhite) ? -16 : 16; // Note: this is capture direction from to, so reversed from normal move direction
    if (((g_board[to + inc + 1] & 0xF) == (piecePawn | them)) ||
        ((g_board[to + inc - 1] & 0xF) == (piecePawn | them))) {
        return false;
    }

    var themAttacks = new Array();

    // Knight attacks 
    // If any opponent knights can capture back, and the deficit we have to make up is greater than the knights value, 
    // it's not worth it.  We can capture on this square again, and the opponent doesn't have to capture back. 
    var captureDeficit = fromValue - toValue;
    SeeAddKnightAttacks(to, them, themAttacks);
    if (themAttacks.length != 0 && captureDeficit > g_seeValues[pieceKnight]) {
        return false;
    }

    // Slider attacks
    g_board[from] = 0;
    for (var pieceType = pieceBishop; pieceType <= pieceQueen; pieceType++) {
        if (SeeAddSliderAttacks(to, them, themAttacks, pieceType)) {
            if (captureDeficit > g_seeValues[pieceType]) {
                g_board[from] = fromPiece;
                return false;
            }
        }
    }

    // Pawn defenses 
    // At this point, we are sure we are making a "losing" capture.  The opponent can not capture back with a 
    // pawn.  They cannot capture back with a minor/major and stand pat either.  So, if we can capture with 
    // a pawn, it's got to be a winning or equal capture. 
    if (((g_board[to - inc + 1] & 0xF) == (piecePawn | us)) ||
        ((g_board[to - inc - 1] & 0xF) == (piecePawn | us))) {
        g_board[from] = fromPiece;
        return true;
    }

    // King attacks
    SeeAddSliderAttacks(to, them, themAttacks, pieceKing);

    // Our attacks
    var usAttacks = new Array();
    SeeAddKnightAttacks(to, us, usAttacks);
    for (var pieceType = pieceBishop; pieceType <= pieceKing; pieceType++) {
        SeeAddSliderAttacks(to, us, usAttacks, pieceType);
    }

    g_board[from] = fromPiece;

    // We are currently winning the amount of material of the captured piece, time to see if the opponent 
    // can get it back somehow.  We assume the opponent can capture our current piece in this score, which 
    // simplifies the later code considerably. 
    var seeValue = toValue - fromValue;

    for (; ; ) {
        var capturingPieceValue = 1000;
        var capturingPieceIndex = -1;

        // Find the least valuable piece of the opponent that can attack the square
        for (var i = 0; i < themAttacks.length; i++) {
            if (themAttacks[i] != 0) {
                var pieceValue = g_seeValues[g_board[themAttacks[i]] & 0x7];
                if (pieceValue < capturingPieceValue) {
                    capturingPieceValue = pieceValue;
                    capturingPieceIndex = i;
                }
            }
        }

        if (capturingPieceIndex == -1) {
            // Opponent can't capture back, we win
            return true;
        }

        // Now, if seeValue < 0, the opponent is winning.  If even after we take their piece, 
        // we can't bring it back to 0, then we have lost this battle. 
        seeValue += capturingPieceValue;
        if (seeValue < 0) {
            return false;
        }

        var capturingPieceSquare = themAttacks[capturingPieceIndex];
        themAttacks[capturingPieceIndex] = 0;

        // Add any x-ray attackers
        SeeAddXrayAttack(to, capturingPieceSquare, us, usAttacks, themAttacks);

        // Our turn to capture
        capturingPieceValue = 1000;
        capturingPieceIndex = -1;

        // Find our least valuable piece that can attack the square
        for (var i = 0; i < usAttacks.length; i++) {
            if (usAttacks[i] != 0) {
                var pieceValue = g_seeValues[g_board[usAttacks[i]] & 0x7];
                if (pieceValue < capturingPieceValue) {
                    capturingPieceValue = pieceValue;
                    capturingPieceIndex = i;
                }
            }
        }

        if (capturingPieceIndex == -1) {
            // We can't capture back, we lose :( 
            return false;
        }

        // Assume our opponent can capture us back, and if we are still winning, we can stand-pat 
        // here, and assume we've won. 
        seeValue -= capturingPieceValue;
        if (seeValue >= 0) {
            return true;
        }

        capturingPieceSquare = usAttacks[capturingPieceIndex];
        usAttacks[capturingPieceIndex] = 0;

        // Add any x-ray attackers
        SeeAddXrayAttack(to, capturingPieceSquare, us, usAttacks, themAttacks);
    }
}

function SeeAddXrayAttack(target, square, us, usAttacks, themAttacks) {
    var index = square - target + 128;
    var delta = -g_vectorDelta[index].delta;
    if (delta == 0)
        return;
    square += delta;
    while (g_board[square] == 0) {
        square += delta;
    }

    if ((g_board[square] & 0x18) && IsSquareOnPieceLine(target, square)) {
        if ((g_board[square] & 8) == us) {
            usAttacks[usAttacks.length] = square;
        } else {
            themAttacks[themAttacks.length] = square;
        }
    }
}

// target = attacking square, us = color of knights to look for, attacks = array to add squares to
function SeeAddKnightAttacks(target, us, attacks) {
    var pieceIdx = (us | pieceKnight) << 4;
    var attackerSq = g_pieceList[pieceIdx++];

    while (attackerSq != 0) {
        if (IsSquareOnPieceLine(target, attackerSq)) {
            attacks[attacks.length] = attackerSq;
        }
        attackerSq = g_pieceList[pieceIdx++];
    }
}

function SeeAddSliderAttacks(target, us, attacks, pieceType) {
    var pieceIdx = (us | pieceType) << 4;
    var attackerSq = g_pieceList[pieceIdx++];
    var hit = false;

    while (attackerSq != 0) {
        if (IsSquareAttackableFrom(target, attackerSq)) {
            attacks[attacks.length] = attackerSq;
            hit = true;
        }
        attackerSq = g_pieceList[pieceIdx++];
    }

    return hit;
}

function BuildPVMessage(bestMove, value, timeTaken, ply) {
    var totalNodes = g_nodeCount + g_qNodeCount;
    return "Ply:" + ply + " Score:" + value + " Nodes:" + totalNodes + " NPS:" + ((totalNodes / (timeTaken / 1000)) | 0) + " " + PVFromHash(bestMove, 15);
}

//////////////////////////////////////////////////
// Test Harness
//////////////////////////////////////////////////
function FinishPlyCallback(bestMove, value, timeTaken, ply) {
    postMessage("pv " + BuildPVMessage(bestMove, value, timeTaken, ply));
}

function FinishMoveLocalTesting(bestMove, value, timeTaken, ply) {
    if (bestMove != null) {
        MakeMove(bestMove);
        postMessage(FormatMove(bestMove));
    }
}

/*
var needsReset = true;
self.onmessage = function (e) {
    if (e.data == "go" || needsReset) {
        ResetGame();
        needsReset = false;
        if (e.data == "go") return;
    }
    if (e.data.match("^position") == "position") {
        ResetGame();
        var result = InitializeFromFen(e.data.substr(9, e.data.length - 9));
        if (result.length != 0) {
            postMessage("message " + result);
        }
    } else if (e.data.match("^search") == "search") {
        g_timeout = parseInt(e.data.substr(7, e.data.length - 7), 10);
        Search(FinishMoveLocalTesting, 99, FinishPlyCallback);
    } else if (e.data == "analyze") {
        g_timeout = 99999999999;
        Search(null, 99, FinishPlyCallback);
    } else {
        //MakeMove(GetMoveFromString(e.data));
		var move = GetMoveFromString(e.data);
		if (move != 'busted') MakeMove(move);
    }
}
*/

var needsReset = true;
self.onmessage = function (e) {
	if (typeof e.data != 'string') return;
    if (e.data == "go" || needsReset) {
        ResetGame();
        needsReset = false;
        if (e.data == "go") return;
    }
    if (e.data.match("^position") == "position") {
        ResetGame();
        var result = InitializeFromFen(e.data.substr(9, e.data.length - 9));
        if (result.length != 0) {
            postMessage("message " + result);
        }
    } else if (e.data.match("^search") == "search") {
        g_timeout = parseInt(e.data.substr(7, e.data.length - 7), 10);
        Search(FinishMoveLocalTesting, 99, FinishPlyCallback);
    } else if (e.data == "analyze") {
        g_timeout = 99999999999;
        Search(null, 99, FinishPlyCallback);
    } else {
        //MakeMove(GetMoveFromString(e.data));
		var move = GetMoveFromString(e.data);
		if (move != 'busted') MakeMove(move);
    }
}


/////////////////////////////////////////////
// overwritten to allow kingless positions and tokens

var g_noBlackKing = false;
var g_noWhiteKing = false;
var pieceToken = 7;
var g_allowNullMove = false;
var g_whitetoken = false;
var g_blacktoken = false;

function InitializeFromFen(fen)
{
	var original_pawnAdj =
	[
	  0, 0, 0, 0, 0, 0, 0, 0,
	  -25, 105, 135, 270, 270, 135, 105, -25,
	  -80, 0, 30, 176, 176, 30, 0, -80,
	  -85, -5, 25, 175, 175, 25, -5, -85,
	  -90, -10, 20, 125, 125, 20, -10, -90,
	  -95, -15, 15, 75, 75, 15, -15, -95, 
	  -100, -20, 10, 70, 70, 10, -20, -100, 
	  0, 0, 0, 0, 0, 0, 0, 0
	];
	var r7 = maxMateBuffer - 100;
	var pawnwars_pawnAdj =
	[
	  0, 0, 0, 0, 0, 0, 0, 0,
	  r7, r7, r7, r7, r7, r7, r7, r7,
	  0, 0, 0, 0, 0, 0, 0, 0,
	  0, 0, 0, 0, 0, 0, 0, 0,
	  0, 0, 0, 0, 0, 0, 0, 0,
	  0, 0, 0, 0, 0, 0, 0, 0,
	  0, 0, 0, 0, 0, 0, 0, 0,
	  0, 0, 0, 0, 0, 0, 0, 0
	];

    var chunks = fen.split(' ');
    
    for (var i = 0; i < 256; i++) 
        g_board[i] = 0x80;
    
    var row = 0;
    var col = 0;
    
    var pieces = chunks[0];
    for (var i = 0; i < pieces.length; i++) {
        var c = pieces.charAt(i);
        
        if (c == '/') {
            row++;
            col = 0;
        }
        else {
            if (c >= '0' && c <= '9') {
                for (var j = 0; j < parseInt(c); j++) {
                    g_board[MakeSquare(row, col)] = 0;
                    col++;
                }
            }
            else {
                var isBlack = c >= 'a' && c <= 'z';
                var piece = isBlack ? colorBlack : colorWhite;
                if (!isBlack) 
                    c = pieces.toLowerCase().charAt(i);
                switch (c) {
                    case 'p':
                        piece |= piecePawn;
                        break;
                    case 'b':
                        piece |= pieceBishop;
                        break;
                    case 'n':
                        piece |= pieceKnight;
                        break;
                    case 'r':
                        piece |= pieceRook;
                        break;
                    case 'q':
                        piece |= pieceQueen;
                        break;
                    case 'k':
                        piece |= pieceKing;
                        break;
                    case 't':
                        piece |= pieceToken;
                        break;						
                }
                
                g_board[MakeSquare(row, col)] = piece;
                col++;
            }
        }
    }
    
	g_noBlackKing = (pieces.indexOf('k') == -1);
	g_noWhiteKing = (pieces.indexOf('K') == -1);
	if (pieces.toLowerCase().indexOf('t') > -1)
	{
		var tokenAdj =
		[
		  0, 0, 0, 0, 0, 0, 0, 0,
		  0, 0, 0, 0, 0, 0, 0, 0,
		  0, 0, 0, 0, 0, 0, 0, 0,
		  0, 0, 0, 0, 0, 0, 0, 0,
		  0, 0, 0, 0, 0, 0, 0, 0,
		  0, 0, 0, 0, 0, 0, 0, 0,
		  0, 0, 0, 0, 0, 0, 0, 0,
		  0, 0, 0, 0, 0, 0, 0, 0
		];
		pieceSquareAdj[pieceToken] = MakeTable(tokenAdj);
		var tokenworth = 11000; // that was the original idea, but modified below
		// so that the value of all opponent tokens is slightly higher than the value of engines's own chessmen (not counting the king)

		function ile(a) { return pieces.split(a).length - 1; }

		var ileOpponentTokens = (g_toMove) ? ile('t') : ile('T');
		var ileOwnTokens = (g_toMove) ? ile('T') : ile('t');
		var ileWhite = ile('Q')*9750+ile('R')*5000+ile('B')*3450+ile('N')*3350+ile('P')*800;
		var ileBlack = ile('q')*9750+ile('r')*5000+ile('b')*3450+ile('n')*3350+ile('p')*800;
		var ileMe = (g_toMove) ? ileWhite : ileBlack;
		var ileHe = (g_toMove) ? ileBlack : ileWhite;
		if (ileOpponentTokens > 0) tokenworth = Math.floor((ileMe + 999) / ileOpponentTokens);
			                  else tokenworth = Math.floor((ileHe + 999) / ileOwnTokens);
		console.log('one token is worth '+tokenworth);
		
		materialTable = [0, 800, 3350, 3450, 5000, 9750, 600000, tokenworth ];
		g_allowNullMove = true;
	}
	g_whitetoken = pieces.indexOf('T') > -1;
	g_blacktoken = pieces.indexOf('t') > -1;
	
	var bierki = pieces.toUpperCase();
	var noK = bierki.indexOf('K') == -1;
	var noQ = bierki.indexOf('Q') == -1;
	var noR = bierki.indexOf('R') == -1;
	var noB = bierki.indexOf('B') == -1;
	var noN = bierki.indexOf('N') == -1;
	var onlyP = noK && noQ && noR && noB && noN;
	pawnAdj = (onlyP) ? pawnwars_pawnAdj : original_pawnAdj;
	
    InitializePieceList();
    
    g_toMove = chunks[1].charAt(0) == 'w' ? colorWhite : 0;
    var them = 8 - g_toMove;
    
    g_castleRights = 0;
    if (chunks[2].indexOf('K') != -1) { 
        if (g_board[MakeSquare(7, 4)] != (pieceKing | colorWhite) ||
            g_board[MakeSquare(7, 7)] != (pieceRook | colorWhite)) {
            return 'Invalid FEN: White kingside castling not allowed';
        }
        g_castleRights |= 1;
    }
    if (chunks[2].indexOf('Q') != -1) {
        if (g_board[MakeSquare(7, 4)] != (pieceKing | colorWhite) ||
            g_board[MakeSquare(7, 0)] != (pieceRook | colorWhite)) {
            return 'Invalid FEN: White queenside castling not allowed';
        }
        g_castleRights |= 2;
    }
    if (chunks[2].indexOf('k') != -1) {
        if (g_board[MakeSquare(0, 4)] != (pieceKing | colorBlack) ||
            g_board[MakeSquare(0, 7)] != (pieceRook | colorBlack)) {
            return 'Invalid FEN: Black kingside castling not allowed';
        }
        g_castleRights |= 4;
    }
    if (chunks[2].indexOf('q') != -1) {
        if (g_board[MakeSquare(0, 4)] != (pieceKing | colorBlack) ||
            g_board[MakeSquare(0, 0)] != (pieceRook | colorBlack)) {
            return 'Invalid FEN: Black queenside castling not allowed';
        }
        g_castleRights |= 8;
    }
    
    g_enPassentSquare = -1;
    if (chunks[3].indexOf('-') == -1) {
	var col = chunks[3].charAt(0).charCodeAt() - 'a'.charCodeAt();
	var row = 8 - (chunks[3].charAt(1).charCodeAt() - '0'.charCodeAt());
	g_enPassentSquare = MakeSquare(row, col);
    }

    var hashResult = SetHash();
    g_hashKeyLow = hashResult.hashKeyLow;
    g_hashKeyHigh = hashResult.hashKeyHigh;

    g_baseEval = 0;
    for (var i = 0; i < 256; i++) {
        if (g_board[i] & colorWhite) {
            g_baseEval += pieceSquareAdj[g_board[i] & 0x7][i];
            g_baseEval += materialTable[g_board[i] & 0x7];
        } else if (g_board[i] & colorBlack) {
            g_baseEval -= pieceSquareAdj[g_board[i] & 0x7][flipTable[i]];
            g_baseEval -= materialTable[g_board[i] & 0x7];
        }
    }
	//var materialTable = [0, 800, 3350, 3450, 5000, 9750, 600000];
	if (g_noWhiteKing) g_baseEval += materialTable[6];
	if (g_noBlackKing) g_baseEval -= materialTable[6];
    if (!g_toMove) g_baseEval = -g_baseEval;

    g_move50 = 0;
    g_inCheck = IsSquareAttackable(g_pieceList[(g_toMove | pieceKing) << 4], them);

	var skip = (g_toMove == colorWhite && g_noBlackKing) || (g_toMove == 0 && g_noWhiteKing);
    // Check for king capture (invalid FEN)
	if (!skip)
    if (IsSquareAttackable(g_pieceList[(them | pieceKing) << 4], g_toMove)) {
        return 'Invalid FEN: Can capture king';
    }

    // Checkmate/stalemate
    if (GenerateValidMoves().length == 0) {
        return g_inCheck ? 'Checkmate' : 'Stalemate';
    }
	//console.log('base eval = '+g_baseEval);
	g_promotion = false;
    return '';
}

function GenerateValidMoves() {
    var moveList = new Array();
    var allMoves = new Array();
    GenerateCaptureMoves(allMoves, null);
    GenerateAllMoves(allMoves);

	if (g_toMove == 0 && g_noBlackKing) return allMoves.reverse();
	if (g_toMove && g_noWhiteKing) return allMoves.reverse();
    
    for (var i = allMoves.length - 1; i >= 0; i--) {
        if (MakeMove(allMoves[i])) {
            moveList[moveList.length] = allMoves[i];
            UnmakeMove(allMoves[i]);
        }
    }
    
    return moveList;
}

function IsSquareAttackableFrom(target, from)
{
	if (!target) return false;
    var index = from - target + 128;
    var piece = g_board[from];
    if (g_vectorDelta[index].pieceMask[(piece >> 3) & 1] & (1 << (piece & 0x7))) {
        // Yes, this square is pseudo-attackable.  Now, check for real attack
		var inc = g_vectorDelta[index].delta;
        do {
			from += inc;
			if (from == target)
				return true;
		} while (g_board[from] == 0);
    }   
    return false;
}

function ExposesCheck(from, kingPos)
{
	if (!kingPos) return false;
    var index = kingPos - from + 128;
    // If a queen can't reach it, nobody can!
    if ((g_vectorDelta[index].pieceMask[0] & (1 << (pieceQueen))) != 0) {
        var delta = g_vectorDelta[index].delta;
        var pos = kingPos + delta;
        while (g_board[pos] == 0) pos += delta;
        
        var piece = g_board[pos];
        if (((piece & (g_board[kingPos] ^ 0x18)) & 0x18) == 0)
            return false;

        // Now see if the piece can actually attack the king
        var backwardIndex = pos - kingPos + 128;
        return (g_vectorDelta[backwardIndex].pieceMask[(piece >> 3) & 1] & (1 << (piece & 0x7))) != 0;
    }
    return false;
}

function Evaluate() {
    var curEval = g_baseEval;

    var evalAdjust = 0;
    // Black queen gone, then cancel white's penalty for king movement
	if (!g_noWhiteKing)
    if (g_pieceList[pieceQueen << 4] == 0)
        evalAdjust -= pieceSquareAdj[pieceKing][g_pieceList[(colorWhite | pieceKing) << 4]];

    // White queen gone, then cancel black's penalty for king movement
	if (!g_noBlackKing)
    if (g_pieceList[(colorWhite | pieceQueen) << 4] == 0) 
        evalAdjust += pieceSquareAdj[pieceKing][flipTable[g_pieceList[pieceKing << 4]]];

    // Black bishop pair
    if (g_pieceCount[pieceBishop] >= 2)
        evalAdjust -= 500;
    // White bishop pair
    if (g_pieceCount[pieceBishop | colorWhite] >= 2)
        evalAdjust += 500;

    var mobility = Mobility(8) - Mobility(0);

    if (g_toMove == 0) {
        // Black
        curEval -= mobility;
        curEval -= evalAdjust;
    }
    else {
        curEval += mobility;
        curEval += evalAdjust;
    }
    
    return curEval;
}

function AllCutNode(ply, depth, beta, allowNull) {
    if (ply <= 0) {
        return QSearch(beta - 1, beta, 0);
    }

    if ((g_nodeCount & 127) == 127) {
        if ((new Date()).getTime() - g_startTime > g_timeout) {
            // Time cutoff
            g_searchValid = false;
            return beta - 1;
        }
    }

    g_nodeCount++;

    if (IsRepDraw())
        return 0;

    // Mate distance pruning
    if (minEval + depth >= beta)
       return beta;

    if (maxEval - (depth + 1) < beta)
	return beta - 1;

    var hashMove = null;
    var hashNode = g_hashTable[g_hashKeyLow & g_hashMask];
    if (hashNode != null && hashNode.lock == g_hashKeyHigh) {
        hashMove = hashNode.bestMove;
        if (hashNode.hashDepth >= ply) {
            var hashValue = hashNode.value;

            // Fixup mate scores
            if (hashValue >= maxMateBuffer)
		hashValue -= depth;
            else if (hashValue <= minMateBuffer)
                hashValue += depth;

            if (hashNode.flags == hashflagExact)
                return hashValue;
            if (hashNode.flags == hashflagAlpha && hashValue < beta)
                return hashValue;
            if (hashNode.flags == hashflagBeta && hashValue >= beta)
                return hashValue;
        }
    }

    // TODO - positional gain?

    if (!g_inCheck &&
        allowNull &&
        beta > minMateBuffer && 
        beta < maxMateBuffer) {
        // Try some razoring
        if (hashMove == null &&
            ply < 4) {
            var razorMargin = 2500 + 200 * ply;
            if (g_baseEval < beta - razorMargin) {
                var razorBeta = beta - razorMargin;
                var v = QSearch(razorBeta - 1, razorBeta, 0);
                if (v < razorBeta)
                    return v;
            }
        }
        
        // TODO - static null move

        // Null move
        if (ply > 1 &&
            g_baseEval >= beta - (ply >= 4 ? 2500 : 0) &&
            // Disable null move if potential zugzwang (no big pieces)
            (g_pieceCount[pieceBishop | g_toMove] != 0 ||
             g_pieceCount[pieceKnight | g_toMove] != 0 ||
             g_pieceCount[pieceRook | g_toMove] != 0 ||
             g_pieceCount[pieceQueen | g_toMove] != 0)) {
            var r = 3 + (ply >= 5 ? 1 : ply / 4);
            if (g_baseEval - beta > 1500) r++;

	        g_toMove = 8 - g_toMove;
	        g_baseEval = -g_baseEval;
	        g_hashKeyLow ^= g_zobristBlackLow;
	        g_hashKeyHigh ^= g_zobristBlackHigh;
			
	        var value = -AllCutNode(ply - r, depth + 1, -(beta - 1), false);

	        g_hashKeyLow ^= g_zobristBlackLow;
	        g_hashKeyHigh ^= g_zobristBlackHigh;
	        g_toMove = 8 - g_toMove;
	        g_baseEval = -g_baseEval;

            if (value >= beta)
	            return beta;
        }
    }

	if (!g_promotion)
	{
		var moveMade = false;
		var realEval = minEval - 1;
		var inCheck = g_inCheck;

		var movePicker = new MovePicker(hashMove, depth, g_killers[depth][0], g_killers[depth][1]);

		for (;;) {
			var currentMove = movePicker.nextMove();
			if (currentMove == 0) {
				break;
			}

			var plyToSearch = ply - 1;

			if (!MakeMove(currentMove)) {
				continue;
			}

			var value;
			var doFullSearch = true;

			if (g_inCheck) {
				// Check extensions
				plyToSearch++;
			} else {
				var reduced = plyToSearch - (movePicker.atMove > 14 ? 2 : 1);

				// Late move reductions
				if (movePicker.stage == 5 && movePicker.atMove > 5 && ply >= 3) {
					value = -AllCutNode(reduced, depth + 1, -(beta - 1), true);
					doFullSearch = (value >= beta);
				}
			}

			if (doFullSearch) {
				value = -AllCutNode(plyToSearch, depth + 1, -(beta  - 1), true);
			}

			moveMade = true;

			UnmakeMove(currentMove);

			if (!g_searchValid) {
				return beta - 1;
			}

			if (value > realEval) {
				if (value >= beta) {
					var histTo = (currentMove >> 8) & 0xFF;
					if (g_board[histTo] == 0) {
						var histPiece = g_board[currentMove & 0xFF] & 0xF;
						historyTable[histPiece][histTo] += ply * ply;
						if (historyTable[histPiece][histTo] > 32767) {
							historyTable[histPiece][histTo] >>= 1;
						}

						if (g_killers[depth][0] != currentMove) {
							g_killers[depth][1] = g_killers[depth][0];
							g_killers[depth][0] = currentMove;
						}
					}

					StoreHash(value, hashflagBeta, ply, currentMove, depth);
					return value;
				}

				realEval = value;
				hashMove = currentMove;
			}
		}
	}
	
    if (g_promotion || !moveMade) {
        // If we have no valid moves it's either stalemate or checkmate
        if (g_inCheck)
            // Checkmate.
            return minEval + depth;
        else 
            // Stalemate, treat like checkmate if we have no king
			{
				var mekingless = (g_toMove && g_noWhiteKing) || (g_toMove == 0 && g_noBlackKing);
				return mekingless ? (minEval + depth) : 0;
			}
    }

    StoreHash(realEval, hashflagAlpha, ply, hashMove, depth);
    
    return realEval;
}
function AlphaBeta(ply, depth, alpha, beta) {
    if (ply <= 0) {
        return QSearch(alpha, beta, 0);
    }

    g_nodeCount++;

    if (depth > 0 && IsRepDraw())
        return 0;

    // Mate distance pruning
    var oldAlpha = alpha;
    alpha = alpha < minEval + depth ? alpha : minEval + depth;
    beta = beta > maxEval - (depth + 1) ? beta : maxEval - (depth + 1);
    if (alpha >= beta)
       return alpha;
   
	if (!g_promotion)
	{
		var hashMove = null;
		var hashFlag = hashflagAlpha;
		var hashNode = g_hashTable[g_hashKeyLow & g_hashMask];
		if (hashNode != null && hashNode.lock == g_hashKeyHigh) {
			hashMove = hashNode.bestMove;
		}
		
		var inCheck = g_inCheck;

		var moveMade = false;
		var realEval = minEval;

		var movePicker = new MovePicker(hashMove, depth, g_killers[depth][0], g_killers[depth][1]);

		for (;;) {
			var currentMove = movePicker.nextMove();
			if (currentMove == 0) {
				break;
			}

			var plyToSearch = ply - 1;

			if (!MakeMove(currentMove)) {
				continue;
			}

			if (g_inCheck) {
				// Check extensions
				plyToSearch++;
			}

			var value;
			if (moveMade) {
				value = -AllCutNode(plyToSearch, depth + 1, -alpha, true);
				if (value > alpha) {
					value = -AlphaBeta(plyToSearch, depth + 1, -beta, -alpha);
				}
			} else {
				value = -AlphaBeta(plyToSearch, depth + 1, -beta, -alpha);
			}

			moveMade = true;

			UnmakeMove(currentMove);

			if (!g_searchValid) {
				return alpha;
			}

			if (value > realEval) {
				if (value >= beta) {
					var histTo = (currentMove >> 8) & 0xFF;
					if (g_board[histTo] == 0) {
						var histPiece = g_board[currentMove & 0xFF] & 0xF;
						historyTable[histPiece][histTo] += ply * ply;
						if (historyTable[histPiece][histTo] > 32767) {
							historyTable[histPiece][histTo] >>= 1;
						}

						if (g_killers[depth][0] != currentMove) {
							g_killers[depth][1] = g_killers[depth][0];
							g_killers[depth][0] = currentMove;
						}
					}

					StoreHash(value, hashflagBeta, ply, currentMove, depth);
					return value;
				}

				if (value > oldAlpha) {
					hashFlag = hashflagExact;
					alpha = value;
				}

				realEval = value;
				hashMove = currentMove;
			}
		}
	}
    if (g_promotion || !moveMade) {
        // If we have no valid moves it's either stalemate or checkmate
        if (inCheck) 
            // Checkmate.
            return minEval + depth;
        else 
			{            // Stalemate
				if (g_allowNullMove) // should be renamed g_tokens
				{
					if ( (g_toMove && g_whitetoken && GetFen().indexOf('T') == -1) ||
					   (g_toMove == 0 && g_blacktoken && GetFen().indexOf('t') == -1) )
					   {
						   return minEval + depth; // having no more tokens loses
					   }
				}
				var mekingless = (g_toMove && g_noWhiteKing) || (g_toMove == 0 && g_noBlackKing);
				return mekingless ? (minEval + depth) : 0;
			}
    }

    StoreHash(realEval, hashFlag, ply, hashMove, depth);
    
    return realEval;
}

var g_promotion = false;

function MakeMove(move) // modified so that promotion sets g_promotion = true if the enemy is kingless
{
	if (move == NULLMOVE) return MakeNULLMOVE();
	
	if (g_allowNullMove) // should be renamed g_tokens
	{
		if ( (g_toMove && g_whitetoken && GetFen().indexOf('T') == -1) ||
		   (g_toMove == 0 && g_blacktoken && GetFen().indexOf('t') == -1) )
		   {
			   return false;
		   }
	}

    var me = g_toMove >> 3;
    var otherColor = 8 - g_toMove; 
    
    var flags = move & 0xFF0000;
    var to = (move >> 8) & 0xFF;
    var from = move & 0xFF;
    var captured = g_board[to];
    var piece = g_board[from];
    var epcEnd = to;

    if (flags & moveflagEPC) {
        epcEnd = me ? (to + 0x10) : (to - 0x10);
        captured = g_board[epcEnd];
        g_board[epcEnd] = pieceEmpty;
    }

    g_moveUndoStack[g_moveCount] = new UndoHistory(g_enPassentSquare, g_castleRights, g_inCheck, g_baseEval, g_hashKeyLow, g_hashKeyHigh, g_move50, captured);
    g_moveCount++;

    g_enPassentSquare = -1;

    if (flags) {
        if (flags & moveflagCastleKing) {
            if (IsSquareAttackable(from + 1, otherColor) ||
            	IsSquareAttackable(from + 2, otherColor)) {
                g_moveCount--;
                return false;
            }
            
            var rook = g_board[to + 1];
            
            g_hashKeyLow ^= g_zobristLow[to + 1][rook & 0xF];
            g_hashKeyHigh ^= g_zobristHigh[to + 1][rook & 0xF];
            g_hashKeyLow ^= g_zobristLow[to - 1][rook & 0xF];
            g_hashKeyHigh ^= g_zobristHigh[to - 1][rook & 0xF];
            
            g_board[to - 1] = rook;
            g_board[to + 1] = pieceEmpty;
            
            g_baseEval -= pieceSquareAdj[rook & 0x7][me == 0 ? flipTable[to + 1] : (to + 1)];
            g_baseEval += pieceSquareAdj[rook & 0x7][me == 0 ? flipTable[to - 1] : (to - 1)];

            var rookIndex = g_pieceIndex[to + 1];
            g_pieceIndex[to - 1] = rookIndex;
            g_pieceList[((rook & 0xF) << 4) | rookIndex] = to - 1;
        } else if (flags & moveflagCastleQueen) {
            if (IsSquareAttackable(from - 1, otherColor) ||
            	IsSquareAttackable(from - 2, otherColor)) {
                g_moveCount--;
                return false;
            }
            
            var rook = g_board[to - 2];

            g_hashKeyLow ^= g_zobristLow[to -2][rook & 0xF];
            g_hashKeyHigh ^= g_zobristHigh[to - 2][rook & 0xF];
            g_hashKeyLow ^= g_zobristLow[to + 1][rook & 0xF];
            g_hashKeyHigh ^= g_zobristHigh[to + 1][rook & 0xF];
            
            g_board[to + 1] = rook;
            g_board[to - 2] = pieceEmpty;
            
            g_baseEval -= pieceSquareAdj[rook & 0x7][me == 0 ? flipTable[to - 2] : (to - 2)];
            g_baseEval += pieceSquareAdj[rook & 0x7][me == 0 ? flipTable[to + 1] : (to + 1)];

            var rookIndex = g_pieceIndex[to - 2];
            g_pieceIndex[to + 1] = rookIndex;
            g_pieceList[((rook & 0xF) << 4) | rookIndex] = to + 1;
        }
    }

	if ((captured & 7) == pieceToken)
	{
		g_baseEval += materialTable[pieceToken];
		g_move50 = 0;
	}
	else
    if (captured) {
        // Remove our piece from the piece list
        var capturedType = captured & 0xF;
        g_pieceCount[capturedType]--;
        var lastPieceSquare = g_pieceList[(capturedType << 4) | g_pieceCount[capturedType]];
        g_pieceIndex[lastPieceSquare] = g_pieceIndex[epcEnd];
        g_pieceList[(capturedType << 4) | g_pieceIndex[lastPieceSquare]] = lastPieceSquare;
        g_pieceList[(capturedType << 4) | g_pieceCount[capturedType]] = 0;

        g_baseEval += materialTable[captured & 0x7];
        g_baseEval += pieceSquareAdj[captured & 0x7][me ? flipTable[epcEnd] : epcEnd];

        g_hashKeyLow ^= g_zobristLow[epcEnd][capturedType];
        g_hashKeyHigh ^= g_zobristHigh[epcEnd][capturedType];
        g_move50 = 0;
    } else if ((piece & 0x7) == piecePawn) {
        var diff = to - from;
        if (diff < 0) diff = -diff;
        if (diff > 16) {
            g_enPassentSquare = me ? (to + 0x10) : (to - 0x10);
        }
        g_move50 = 0;
    }

    g_hashKeyLow ^= g_zobristLow[from][piece & 0xF];
    g_hashKeyHigh ^= g_zobristHigh[from][piece & 0xF];
    g_hashKeyLow ^= g_zobristLow[to][piece & 0xF];
    g_hashKeyHigh ^= g_zobristHigh[to][piece & 0xF];
    g_hashKeyLow ^= g_zobristBlackLow;
    g_hashKeyHigh ^= g_zobristBlackHigh;
    
    g_castleRights &= g_castleRightsMask[from] & g_castleRightsMask[to];

    g_baseEval -= pieceSquareAdj[piece & 0x7][me == 0 ? flipTable[from] : from];
    
    // Move our piece in the piece list
    g_pieceIndex[to] = g_pieceIndex[from];
    g_pieceList[((piece & 0xF) << 4) | g_pieceIndex[to]] = to;

    if (flags & moveflagPromotion) { /////////////////////////////////////////////////////////////////////////////
		if (g_noBlackKing && g_toMove) g_promotion = true; ///////////////////////////////////////////////////////
		if (g_noWhiteKing && g_toMove == 0) g_promotion = true; //////////////////////////////////////////////////
        var newPiece = piece & (~0x7);
        if (flags & moveflagPromoteKnight) 
            newPiece |= pieceKnight;
        else if (flags & moveflagPromoteQueen) 
            newPiece |= pieceQueen;
        else if (flags & moveflagPromoteBishop) 
            newPiece |= pieceBishop;
        else 
            newPiece |= pieceRook;

        g_hashKeyLow ^= g_zobristLow[to][piece & 0xF];
        g_hashKeyHigh ^= g_zobristHigh[to][piece & 0xF];
        g_board[to] = newPiece;
        g_hashKeyLow ^= g_zobristLow[to][newPiece & 0xF];
        g_hashKeyHigh ^= g_zobristHigh[to][newPiece & 0xF];
        
        g_baseEval += pieceSquareAdj[newPiece & 0x7][me == 0 ? flipTable[to] : to];
        g_baseEval -= materialTable[piecePawn];
        g_baseEval += materialTable[newPiece & 0x7];

        var pawnType = piece & 0xF;
        var promoteType = newPiece & 0xF;

        g_pieceCount[pawnType]--;

        var lastPawnSquare = g_pieceList[(pawnType << 4) | g_pieceCount[pawnType]];
        g_pieceIndex[lastPawnSquare] = g_pieceIndex[to];
        g_pieceList[(pawnType << 4) | g_pieceIndex[lastPawnSquare]] = lastPawnSquare;
        g_pieceList[(pawnType << 4) | g_pieceCount[pawnType]] = 0;
        g_pieceIndex[to] = g_pieceCount[promoteType];
        g_pieceList[(promoteType << 4) | g_pieceIndex[to]] = to;
        g_pieceCount[promoteType]++;
    } else {
        g_board[to] = g_board[from];
        
        g_baseEval += pieceSquareAdj[piece & 0x7][me == 0 ? flipTable[to] : to];
    }
    g_board[from] = pieceEmpty;

    g_toMove = otherColor;
    g_baseEval = -g_baseEval;
    
    if ((piece & 0x7) == pieceKing || g_inCheck) {
        if (IsSquareAttackable(g_pieceList[(pieceKing | (8 - g_toMove)) << 4], otherColor)) {
            UnmakeMove(move);
            return false;
        }
    } else {
        var kingPos = g_pieceList[(pieceKing | (8 - g_toMove)) << 4];
        
        if (ExposesCheck(from, kingPos)) {
            UnmakeMove(move);
            return false;
        }
        
        if (epcEnd != to) {
            if (ExposesCheck(epcEnd, kingPos)) {
                UnmakeMove(move);
                return false;
            }
        }
    }
    
    g_inCheck = false;
    
    if (flags <= moveflagEPC) {
        var theirKingPos = g_pieceList[(pieceKing | g_toMove) << 4];
        
        // First check if the piece we moved can attack the enemy king
        g_inCheck = IsSquareAttackableFrom(theirKingPos, to);
        
        if (!g_inCheck) {
            // Now check if the square we moved from exposes check on the enemy king
            g_inCheck = ExposesCheck(from, theirKingPos);
            
            if (!g_inCheck) {
                // Finally, ep. capture can cause another square to be exposed
                if (epcEnd != to) {
                    g_inCheck = ExposesCheck(epcEnd, theirKingPos);
                }
            }
        }
    }
    else {
        // Castle or promotion, slow check
        g_inCheck = IsSquareAttackable(g_pieceList[(pieceKing | g_toMove) << 4], 8 - g_toMove);
    }

    g_repMoveStack[g_moveCount - 1] = g_hashKeyLow;
    g_move50++;

    return true;
}
function UnmakeMove(move){ g_promotion = false; // my one line modification

  if (move == NULLMOVE) { UnmakeNULLMOVE(); return; }

    g_toMove = 8 - g_toMove;
    g_baseEval = -g_baseEval;
    
    g_moveCount--;
    g_enPassentSquare = g_moveUndoStack[g_moveCount].ep;
    g_castleRights = g_moveUndoStack[g_moveCount].castleRights;
    g_inCheck = g_moveUndoStack[g_moveCount].inCheck;
    g_baseEval = g_moveUndoStack[g_moveCount].baseEval;
    g_hashKeyLow = g_moveUndoStack[g_moveCount].hashKeyLow;
    g_hashKeyHigh = g_moveUndoStack[g_moveCount].hashKeyHigh;
    g_move50 = g_moveUndoStack[g_moveCount].move50;
    
    var otherColor = 8 - g_toMove;
    var me = g_toMove >> 3;
    var them = otherColor >> 3;
    
    var flags = move & 0xFF0000;
    var captured = g_moveUndoStack[g_moveCount].captured;
    var to = (move >> 8) & 0xFF;
    var from = move & 0xFF;
    
    var piece = g_board[to];
    
    if (flags) {
        if (flags & moveflagCastleKing) {
            var rook = g_board[to - 1];
            g_board[to + 1] = rook;
            g_board[to - 1] = pieceEmpty;
			
            var rookIndex = g_pieceIndex[to - 1];
            g_pieceIndex[to + 1] = rookIndex;
            g_pieceList[((rook & 0xF) << 4) | rookIndex] = to + 1;
        }
        else if (flags & moveflagCastleQueen) {
            var rook = g_board[to + 1];
            g_board[to - 2] = rook;
            g_board[to + 1] = pieceEmpty;
			
            var rookIndex = g_pieceIndex[to + 1];
            g_pieceIndex[to - 2] = rookIndex;
            g_pieceList[((rook & 0xF) << 4) | rookIndex] = to - 2;
        }
    }
    
    if (flags & moveflagPromotion) {
        piece = (g_board[to] & (~0x7)) | piecePawn;
        g_board[from] = piece;

        var pawnType = g_board[from] & 0xF;
        var promoteType = g_board[to] & 0xF;

        g_pieceCount[promoteType]--;

        var lastPromoteSquare = g_pieceList[(promoteType << 4) | g_pieceCount[promoteType]];
        g_pieceIndex[lastPromoteSquare] = g_pieceIndex[to];
        g_pieceList[(promoteType << 4) | g_pieceIndex[lastPromoteSquare]] = lastPromoteSquare;
        g_pieceList[(promoteType << 4) | g_pieceCount[promoteType]] = 0;
        g_pieceIndex[to] = g_pieceCount[pawnType];
        g_pieceList[(pawnType << 4) | g_pieceIndex[to]] = to;
        g_pieceCount[pawnType]++;
    }
    else {
        g_board[from] = g_board[to];
    }

    var epcEnd = to;
    if (flags & moveflagEPC) {
        if (g_toMove == colorWhite) 
            epcEnd = to + 0x10;
        else 
            epcEnd = to - 0x10;
        g_board[to] = pieceEmpty;
    }
    
    g_board[epcEnd] = captured;

	// Move our piece in the piece list
    g_pieceIndex[from] = g_pieceIndex[to];
    g_pieceList[((piece & 0xF) << 4) | g_pieceIndex[from]] = from;

	if ((captured & 7) == pieceToken) { } else
    if (captured) {
		// Restore our piece to the piece list
        var captureType = captured & 0xF;
        g_pieceIndex[epcEnd] = g_pieceCount[captureType];
        g_pieceList[(captureType << 4) | g_pieceCount[captureType]] = epcEnd;
        g_pieceCount[captureType]++;
    }
}

function GetFen(){
    var result = "";
    for (var row = 0; row < 8; row++) {
        if (row != 0) 
            result += '/';
        var empty = 0;
        for (var col = 0; col < 8; col++) {
            var piece = g_board[((row + 2) << 4) + col + 4];
            if (piece == 0) {
                empty++;
            }
            else {
                if (empty != 0) 
                    result += empty;
                empty = 0;
                
                var pieceChar = [" ", "p", "n", "b", "r", "q", "k", "t"][(piece & 0x7)];
                result += ((piece & colorWhite) != 0) ? pieceChar.toUpperCase() : pieceChar;
            }
        }
        if (empty != 0) {
            result += empty;
        }
    }
    
    result += g_toMove == colorWhite ? " w" : " b";
    result += " ";
    if (g_castleRights == 0) {
        result += "-";
    }
    else {
        if ((g_castleRights & 1) != 0) 
            result += "K";
        if ((g_castleRights & 2) != 0) 
            result += "Q";
        if ((g_castleRights & 4) != 0) 
            result += "k";
        if ((g_castleRights & 8) != 0) 
            result += "q";
    }
    
    result += " ";
    
    if (g_enPassentSquare == -1) {
        result += '-';
    }
    else {
        result += FormatSquare(g_enPassentSquare);
    }
    
    return result;
}

function InitializeEval() {
    g_mobUnit = new Array(2);
    for (var i = 0; i < 2; i++) {
        g_mobUnit[i] = new Array();
        var enemy = i == 0 ? 0x10 : 8;
        var friend = i == 0 ? 8 : 0x10;
        g_mobUnit[i][0] = 1;
        g_mobUnit[i][0x80] = 0;
        g_mobUnit[i][enemy | piecePawn] = 1;
        g_mobUnit[i][enemy | pieceBishop] = 2;
        g_mobUnit[i][enemy | pieceKnight] = 2;
        g_mobUnit[i][enemy | pieceRook] = 4;
        g_mobUnit[i][enemy | pieceQueen] = 6;
        g_mobUnit[i][enemy | pieceKing] = 6;
		g_mobUnit[i][enemy | pieceToken] = 6;
        g_mobUnit[i][friend | piecePawn] = 0;
        g_mobUnit[i][friend | pieceBishop] = 0;
        g_mobUnit[i][friend | pieceKnight] = 0;
        g_mobUnit[i][friend | pieceRook] = 0;
        g_mobUnit[i][friend | pieceQueen] = 0;
        g_mobUnit[i][friend | pieceKing] = 0;
		g_mobUnit[i][friend | pieceToken] = 0;
    }
}

function MakeNULLMOVE()
{
	if (g_inCheck) return false;
    g_moveUndoStack[g_moveCount] = new UndoHistory(g_enPassentSquare, g_castleRights, g_inCheck, g_baseEval, g_hashKeyLow, g_hashKeyHigh, g_move50, 0);
    g_moveCount++;
    g_enPassentSquare = -1;
    g_toMove = 8 - g_toMove;
    g_baseEval = -g_baseEval+1001;
    //g_repMoveStack[g_moveCount - 1] = g_hashKeyLow;
    g_move50++;
    return true;
}
function UnmakeNULLMOVE()
{
	g_promotion = false;
    g_toMove = 8 - g_toMove;
    g_baseEval = -g_baseEval+1001;
    g_moveCount--;
    g_enPassentSquare = g_moveUndoStack[g_moveCount].ep;
    g_castleRights = g_moveUndoStack[g_moveCount].castleRights;
    g_inCheck = g_moveUndoStack[g_moveCount].inCheck;
    g_baseEval = g_moveUndoStack[g_moveCount].baseEval;
    g_hashKeyLow = g_moveUndoStack[g_moveCount].hashKeyLow;
    g_hashKeyHigh = g_moveUndoStack[g_moveCount].hashKeyHigh;
    g_move50 = g_moveUndoStack[g_moveCount].move50;    
}

function GenerateCaptureMoves(moveStack, moveScores)
{
	if (g_allowNullMove) // should be renamed g_tokens
	{
		if ( (g_toMove && g_whitetoken && GetFen().indexOf('T') == -1) ||
		   (g_toMove == 0 && g_blacktoken && GetFen().indexOf('t') == -1) )
		   {
			   moveStack = [];
			   return;
		   }
	}

    var from, to, piece, pieceIdx;
    var inc = (g_toMove == 8) ? -16 : 16;
    var enemy = g_toMove == 8 ? 0x10 : 0x8;

    // Pawn captures
    pieceIdx = (g_toMove | 1) << 4;
    from = g_pieceList[pieceIdx++];
    while (from != 0) {
        to = from + inc - 1;
        if (g_board[to] & enemy) {
            MovePawnTo(moveStack, from, to);
        }

        to = from + inc + 1;
        if (g_board[to] & enemy) {
            MovePawnTo(moveStack, from, to);
        }

        from = g_pieceList[pieceIdx++];
    }

    if (g_enPassentSquare != -1) {
        var inc = (g_toMove == colorWhite) ? -16 : 16;
        var pawn = g_toMove | piecePawn;

        var from = g_enPassentSquare - (inc + 1);
        if ((g_board[from] & 0xF) == pawn) {
            moveStack[moveStack.length] = GenerateMove(from, g_enPassentSquare, moveflagEPC);
        }

        from = g_enPassentSquare - (inc - 1);
        if ((g_board[from] & 0xF) == pawn) {
            moveStack[moveStack.length] = GenerateMove(from, g_enPassentSquare, moveflagEPC);
        }
    }

    // Knight captures
	pieceIdx = (g_toMove | 2) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from + 31; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 33; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 14; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 14; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 31; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 33; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 18; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 18; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		from = g_pieceList[pieceIdx++];
	}
	
	// Bishop captures
	pieceIdx = (g_toMove | 3) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from; do { to -= 15; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to -= 17; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to += 15; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to += 17; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		from = g_pieceList[pieceIdx++];
	}
	
	// Rook captures
	pieceIdx = (g_toMove | 4) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from; do { to--; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to++; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to -= 16; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to += 16; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		from = g_pieceList[pieceIdx++];
	}
	
	// Queen captures
	pieceIdx = (g_toMove | 5) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from; do { to -= 15; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to -= 17; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to += 15; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to += 17; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to--; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to++; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to -= 16; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from; do { to += 16; } while (g_board[to] == 0); if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		from = g_pieceList[pieceIdx++];
	}
	
	// King captures
	{
		pieceIdx = (g_toMove | 6) << 4;
		from = g_pieceList[pieceIdx];
		to = from - 15; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 17; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 15; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 17; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 1; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 1; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 16; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 16; if (g_board[to] & enemy) moveStack[moveStack.length] = GenerateMove(from, to);
	}
}

function GenerateAllMoves(moveStack) // modified to allow nullmove
{	
	if (g_allowNullMove) // should be renamed g_tokens
	{
		if ( (g_toMove && g_whitetoken && GetFen().indexOf('T') == -1) ||
		   (g_toMove == 0 && g_blacktoken && GetFen().indexOf('t') == -1) )
		   {
			   moveStack = [];
			   return;
		   }
		if ((g_toMove && White_has_tokens_only()) || (g_toMove==0 && Black_has_tokens_only()))
			moveStack[moveStack.length] = NULLMOVE;
	}

    var from, to, piece, pieceIdx;

	// Pawn quiet moves
    pieceIdx = (g_toMove | 1) << 4;
    from = g_pieceList[pieceIdx++];
    while (from != 0) {
        GeneratePawnMoves(moveStack, from);
        from = g_pieceList[pieceIdx++];
    }

    // Knight quiet moves
	pieceIdx = (g_toMove | 2) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from + 31; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 33; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 14; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 14; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 31; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 33; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 18; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 18; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		from = g_pieceList[pieceIdx++];
	}

	// Bishop quiet moves
	pieceIdx = (g_toMove | 3) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from - 15; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to -= 15; }
		to = from - 17; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to -= 17; }
		to = from + 15; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to += 15; }
		to = from + 17; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to += 17; }
		from = g_pieceList[pieceIdx++];
	}

	// Rook quiet moves
	pieceIdx = (g_toMove | 4) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from - 1; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to--; }
		to = from + 1; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to++; }
		to = from + 16; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to += 16; }
		to = from - 16; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to -= 16; }
		from = g_pieceList[pieceIdx++];
	}
	
	// Queen quiet moves
	pieceIdx = (g_toMove | 5) << 4;
	from = g_pieceList[pieceIdx++];
	while (from != 0) {
		to = from - 15; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to -= 15; }
		to = from - 17; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to -= 17; }
		to = from + 15; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to += 15; }
		to = from + 17; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to += 17; }
		to = from - 1; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to--; }
		to = from + 1; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to++; }
		to = from + 16; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to += 16; }
		to = from - 16; while (g_board[to] == 0) { moveStack[moveStack.length] = GenerateMove(from, to); to -= 16; }
		from = g_pieceList[pieceIdx++];
	}
	
	// King quiet moves
	{
		pieceIdx = (g_toMove | 6) << 4;
		from = g_pieceList[pieceIdx];
		to = from - 15; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 17; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 15; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 17; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 1; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 1; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from - 16; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		to = from + 16; if (g_board[to] == 0) moveStack[moveStack.length] = GenerateMove(from, to);
		
        if (!g_inCheck) {
            var castleRights = g_castleRights;
            if (!g_toMove) 
                castleRights >>= 2;
            if (castleRights & 1) {
                // Kingside castle
                if (g_board[from + 1] == pieceEmpty && g_board[from + 2] == pieceEmpty) {
                    moveStack[moveStack.length] = GenerateMove(from, from + 0x02, moveflagCastleKing);
                }
            }
            if (castleRights & 2) {
                // Queenside castle
                if (g_board[from - 1] == pieceEmpty && g_board[from - 2] == pieceEmpty && g_board[from - 3] == pieceEmpty) {
                    moveStack[moveStack.length] = GenerateMove(from, from - 0x02, moveflagCastleQueen);
                }
            }
        }
	}
}

function White_has_tokens_only()
{
	var fen = GetFen().split(' ')[0];
	if (fen.indexOf('K')>-1) return false;
	if (fen.indexOf('Q')>-1) return false;
	if (fen.indexOf('R')>-1) return false;
	if (fen.indexOf('B')>-1) return false;
	if (fen.indexOf('N')>-1) return false;
	if (fen.indexOf('P')>-1) return false;
	return (fen.indexOf('T')>-1);
}
function Black_has_tokens_only()
{
	var fen = GetFen().split(' ')[0];
	if (fen.indexOf('k')>-1) return false;
	if (fen.indexOf('q')>-1) return false;
	if (fen.indexOf('r')>-1) return false;
	if (fen.indexOf('b')>-1) return false;
	if (fen.indexOf('n')>-1) return false;
	if (fen.indexOf('p')>-1) return false;
	return (fen.indexOf('t')>-1);
}

function PVFromHash() { return ''; }
function PVFromHash(move, ply)
{
	if (g_allowNullMove) return '';
	
    if (ply == 0) 
        return "";

    if (move == 0) {
	if (g_inCheck) return "checkmate";
	return "stalemate";
    }
    
    var pvString = " " + GetMoveSAN(move);
    MakeMove(move);
    
    var hashNode = g_hashTable[g_hashKeyLow & g_hashMask];
    if (hashNode != null && hashNode.lock == g_hashKeyHigh && hashNode.bestMove != null) {
        pvString += PVFromHash(hashNode.bestMove, ply - 1);
    }
    
    UnmakeMove(move);
    
    return pvString;
}
//var garbochesspath = wizboardpath + "garbochess/garbochess.js";

function czyromantic()
{
 return garbochesspath.indexOf('romantic') > 0;
}

var g_fens = new Array();
var g_startOffset = null;
var g_selectedPiece = null;
var moveNumber = 1;

var g_allMoves = [];
var g_playerWhite = true;
var g_changingFen = false;
var g_analyzing = false;

var g_uiBoard;
var g_cellSize = 45;

function UINewGame() {
    moveNumber = 1;

    var pgnTextBox = document.getElementById("PgnTextBox");
    pgnTextBox.value = "";

    EnsureAnalysisStopped();
    ResetGame();
    if (InitializeBackgroundEngine()) {
        g_backgroundEngine.postMessage("go");
		//console.log('background engine to go in UINewGame');
    }
    g_allMoves = [];
    RedrawBoard();

    if (!g_playerWhite) {
    //    SearchAndRedraw();
    }
}

function EnsureAnalysisStopped() {
    if (g_analyzing && g_backgroundEngine != null) {
        g_backgroundEngine.terminate();
        g_backgroundEngine = null;
    }
}

function UIAnalyzeToggle() {
    if (InitializeBackgroundEngine()) {
        if (!g_analyzing) {
            g_backgroundEngine.postMessage("analyze");
        } else {
            EnsureAnalysisStopped();
        }
        g_analyzing = !g_analyzing;
        document.getElementById("AnalysisToggleLink").innerText = g_analyzing ? "Analysis: On" : "Analysis: Off";
    } else {
        alert("Your browser must support web workers for analysis - (chrome4, ff4, safari)");
    }
}

function UIChangeFEN() { updateczyjruch();

    if (!g_changingFen) {
        var fenTextBox = document.getElementById("FenTextBox");
        var result = InitializeFromFen(fenTextBox.value);
        if (result.length != 0) {
            UpdatePVDisplay(result);
            return;
        } else {
            UpdatePVDisplay('');
        }
        g_allMoves = [];

        EnsureAnalysisStopped();
        InitializeBackgroundEngine();

        g_playerWhite = !!g_toMove;
        g_backgroundEngine.postMessage("position " + GetFen());

        RedrawBoard();
        g_fens[0] = fenTextBox.value;
    }
}

function UIChangeStartPlayer() {
    g_playerWhite = !g_playerWhite;
    RedrawBoard();
}



function UpdatePgnTextBox(move) {
    var pgnTextBox = document.getElementById("PgnTextBox");
    //if (g_toMove != 0) {
    //    pgnTextBox.value += moveNumber + ". ";
    //    moveNumber++;
    //}
    pgnTextBox.value += GetMoveSAN(move) + " ";
}

function UIChangeTimePerMove() {
    var timePerMove = document.getElementById("TimePerMove");
    g_timeout = parseInt(timePerMove.value, 10);
}

function FinishMove(bestMove, value, timeTaken, ply) {
    if (bestMove != null) {
        UIPlayMove(bestMove, BuildPVMessage(bestMove, value, timeTaken, ply));
    } else {
        alert("Checkmate!");
    }
}

function UIPlayMove(move, pv) {
/*	g_stateline_droptail();
    UpdatePgnTextBox(move);
    g_allMoves[g_allMoves.length] = move;
    MakeMove(move);
    UpdatePVDisplay(pv);
    UpdateFromMove(move);
*/
	console.log(el('output').innerHTML);
	g_stateline_makemove(move,'engine');
    onComputerMove(move);
}

/*
function UIUndoMove() {
  if (g_allMoves.length == 0) {
    return;
  }

  if (g_backgroundEngine != null) {
    g_backgroundEngine.terminate();
    g_backgroundEngine = null;
  }

  UnmakeMove(g_allMoves[g_allMoves.length - 1]);
  g_allMoves.pop();

  //take back only one half-move
  if (false) if (g_playerWhite != !!g_toMove && g_allMoves.length != 0) {
    UnmakeMove(g_allMoves[g_allMoves.length - 1]);
    g_allMoves.pop();
  }

  RedrawBoard();
}
*/

function UpdatePVDisplay(pv) { //return;                               
    if (pv != null) {
        document.getElementById("output").innerHTML = pv; return;
        var outputDiv = document.getElementById("output");
        if (outputDiv.firstChild != null) {
            outputDiv.removeChild(outputDiv.firstChild);
        }
        outputDiv.appendChild(document.createTextNode(pv));
    }
}

function SearchAndRedraw() {
    if (GameOver) return;
    if (document.getElementById('nocomp').checked) return;
	if (GenerateValidMoves().length == 0) { g_stateline_makemove(null); return; }
    enginethinkingstyle();

    if (g_analyzing)
	{
        EnsureAnalysisStopped();
        InitializeBackgroundEngine();
        g_backgroundEngine.postMessage("position " + GetFen());
        g_backgroundEngine.postMessage("analyze");
        return;
    }
	
	if (g_whitetoken || g_blacktoken)
	{
        EnsureAnalysisStopped();
        InitializeBackgroundEngine();
        g_backgroundEngine.postMessage("position " + GetFen());
		g_backgroundEngine.postMessage("search " + g_timeout);
		return;
	}
	
    if (InitializeBackgroundEngine()) {
        g_backgroundEngine.postMessage("search " + g_timeout);
    } else {
	Search(FinishMove, 99, null);
    }
}

var g_backgroundEngineValid = true;
var g_backgroundEngine;

function InitializeBackgroundEngine() {
    if (!g_backgroundEngineValid) {
        return false;
    }

    if (g_backgroundEngine == null) {
        g_backgroundEngineValid = true;
        try {
            g_backgroundEngine = new Worker(garbochesspath);
            g_backgroundEngine.onmessage = function (e) {
                if (e.data.match("^pv") == "pv") {
                    UpdatePVDisplay(e.data.substr(3, e.data.length - 3));
                } else if (e.data.match("^message") == "message") {
                    EnsureAnalysisStopped();
                    UpdatePVDisplay(e.data.substr(8, e.data.length - 8));
                } else {
                    UIPlayMove(GetMoveFromString(e.data), null);
                }
            }
            g_backgroundEngine.error = function (e) {
                alert("Error from background worker:" + e.message);
            }
            g_backgroundEngine.postMessage("position " + GetFen());
        } catch (error) {
            g_backgroundEngineValid = false;
        }
    }

    return g_backgroundEngineValid;
}

/* original
function UpdateFromMove(move) {
    var fromX = (move & 0xF) - 4;
    var fromY = ((move >> 4) & 0xF) - 2;
    var toX = ((move >> 8) & 0xF) - 4;
    var toY = ((move >> 12) & 0xF) - 2;

    if (!g_playerWhite) {
        fromY = 7 - fromY;
        toY = 7 - toY;
        fromX = 7 - fromX;
        toX = 7 - toX;
    }

    if ((move & moveflagCastleKing) ||
        (move & moveflagCastleQueen) ||
        (move & moveflagEPC) ||
        (move & moveflagPromotion)) {
        RedrawPieces();
    } else {
        var fromSquare = g_uiBoard[fromY * 8 + fromX];
        $(g_uiBoard[toY * 8 + toX])
            .empty()
            .append($(fromSquare).children());
    }
}
*/

function UpdateFromMove(move)
{
	/*function showghost(move)
	{
		var fromX = (move & 0xF) - 4, fromY = ((move >> 4) & 0xF) - 2;
		var toX = ((move >> 8) & 0xF) - 4, toY = ((move >> 12) & 0xF) - 2;
		if (!g_playerWhite) { fromY = 7 - fromY; toY = 7 - toY; fromX = 7 - fromX; toX = 7 - toX; }
		var fromSquare = g_uiBoard[fromY * 8 + fromX], toSquare = g_uiBoard[toY * 8 + toX];
		var towhat;
		if (!toSquare.querySelector('svg')) towhat = ''; else towhat = toSquare.querySelector('svg').getAttribute('class');
		var fromwhat = fromSquare.querySelector('svg').getAttribute('class');
		towhat = (towhat) ? towhat : ''; fromwhat = (fromwhat) ? fromwhat : '';
		var ghost = toSquare.querySelector('svg');
		if (czyromantic()) ghost.setAttribute('transform','scale(-1 1)');
		$(toSquare).empty().append($(fromSquare).children());
		for (var y = 0; y < 8; ++y) for (var x = 0; x < 8; ++x) g_uiBoard[y * 8 + x].style.backgroundImage = 'none';
		if (towhat) // if capture
		{
			//console.log([fromwhat,'-->',towhat]);
			if (towhat.toLowerCase() == fromwhat.toLowerCase())
			{
				var transform = 'rotate(180)';
				if (towhat.toLowerCase() == 'r') transform = 'rotate(90)';
				if (towhat.toLowerCase() == 'n') transform = (!czyromantic()) ? 'scale(-1 1)' : 'scale(1 1)';
				ghost.setAttribute('transform',transform);
			}
			var div = newel('div'); div.appendChild(ghost);
			var svgcode = div.innerHTML;
			svgcode = svgcode.replace(/#000/g,'rgb(0,0,0,0.2)');
			svgcode = svgcode.replace(/#fff/g,'rgb(255,255,255,0.2)');
			toSquare.style.backgroundImage = "url('data:image/svg+xml;utf8,"+escape(svgcode)+"')";
			toSquare.style.backgroundSize = 'cover';
		}

	}*/
	RedrawBoard(); return;
    var fromX = (move & 0xF) - 4, fromY = ((move >> 4) & 0xF) - 2;
    var toX = ((move >> 8) & 0xF) - 4, toY = ((move >> 12) & 0xF) - 2;
    if (!g_playerWhite) { fromY = 7 - fromY; toY = 7 - toY; fromX = 7 - fromX; toX = 7 - toX; }
    if ((move & moveflagCastleKing) || (move & moveflagCastleQueen) || (move & moveflagEPC) || (move & moveflagPromotion))
	{
        RedrawPieces(); return;
    }
	//showghost(move);
}

function pieceSVG(pieceName)
{
 function svgimage(pieceName)
 {
	 switch(pieceName)
	 {
	  case 'king_white' : return svgmerida('K');
	  case 'king_black' : return svgmerida('k');
	  case 'queen_white' : return svgmerida('Q');
	  case 'queen_black' : return svgmerida('q');
	  case 'rook_white' : return svgmerida('R');
	  case 'rook_black' : return svgmerida('r');
	  case 'bishop_white' : return svgmerida('B');
	  case 'bishop_black' : return svgmerida('b');
	  case 'knight_white' : return svgmerida('N');
	  case 'knight_black' : return svgmerida('n');
	  case 'pawn_white' : return svgmerida('P');
	  case 'pawn_black' : return svgmerida('p');
	  case 'token_white' : return svgmerida('T');
	  case 'token_black' : return svgmerida('t');
	 }
	 return blankimage();
 }
 function niemaimage(pieceName)
 {
  if (document.getElementById('edytorek') == null) return blankimage();
  var img = svgimage(pieceName);
  img.style.opacity = '0.3';
  return img;
 }
	
 if (el('blindfold').checked) return niemaimage(pieceName);
 
 if (niema.indexOf('K')>-1) if (pieceName == 'king_white') return niemaimage(pieceName);
 if (niema.indexOf('Q')>-1) if (pieceName == 'queen_white') return niemaimage(pieceName);
 if (niema.indexOf('R')>-1) if (pieceName == 'rook_white') return niemaimage(pieceName);
 if (niema.indexOf('B')>-1) if (pieceName == 'bishop_white') return niemaimage(pieceName);
 if (niema.indexOf('N')>-1) if (pieceName == 'knight_white') return niemaimage(pieceName);
 if (niema.indexOf('P')>-1) if (pieceName == 'pawn_white') return niemaimage(pieceName);
 if (niema.indexOf('k')>-1) if (pieceName == 'king_black') return niemaimage(pieceName);
 if (niema.indexOf('q')>-1) if (pieceName == 'queen_black') return niemaimage(pieceName);
 if (niema.indexOf('r')>-1) if (pieceName == 'rook_black') return niemaimage(pieceName);
 if (niema.indexOf('b')>-1) if (pieceName == 'bishop_black') return niemaimage(pieceName);
 if (niema.indexOf('n')>-1) if (pieceName == 'knight_black') return niemaimage(pieceName);
 if (niema.indexOf('p')>-1) if (pieceName == 'pawn_black') return niemaimage(pieceName);

 return svgimage(pieceName);
}

// this was copied to settingsicon.js
//var darkR = 13*16, darkG = 13*16, darkB = 13*16, lightR = 15*16, lightG = 15*16, lightB = 15*16;
//var darkR = 14*16, darkG = 14*16, darkB = 14*16, lightR = 255, lightG = 255, lightB = 255;
//function rgb(r,g,b) { return 'rgb('+r+','+g+','+b+')'; }
//function darksquarecolor() { return rgb(darkR,darkG,darkB); }
//function lightsquarecolor() { return rgb(lightR,lightG,lightB); }

function selectedPieceBackground(canmove)
{
	return (canmove) ? 'rgb(0,255,255,0.5)' : 'rgb(0,255,255,0.1)';
}

function RecolorBoardSquares(dark,light)
{
    for (y = 0; y < 8; ++y) {
        for (x = 0; x < 8; ++x) {
            var td = g_uiBoard[y * 8 + x];
			td.style.backgroundColor = ((y ^ x) & 1) ? dark : light;
		}
	}
}

function RedrawPieces() {
    for (y = 0; y < 8; ++y) {
        for (x = 0; x < 8; ++x) {
            var td = g_uiBoard[y * 8 + x];
			td.style.backgroundColor = ((y ^ x) & 1) ? darksquarecolor() : lightsquarecolor();
			td.style.transform = 'none';
			td.style.minWidth = g_cellSize+'px';
            var pieceY = g_playerWhite ? y : 7 - y;
            var piece = g_board[((pieceY + 2) * 0x10) + (g_playerWhite ? x : 7 - x) + 4];
            var pieceName = null;
            switch (piece & 0x7) {
                case piecePawn: pieceName = "pawn"; break;
                case pieceKnight: pieceName = "knight"; break;
                case pieceBishop: pieceName = "bishop"; break;
                case pieceRook: pieceName = "rook"; break;
                case pieceQueen: pieceName = "queen"; break;
                case pieceKing: pieceName = "king"; break;
				case pieceToken: pieceName = "token"; break;
            }
            if (pieceName != null) {
                pieceName += "_";
                pieceName += (piece & 0x8) ? "white" : "black";
            }

            if (pieceName != null) {
                var img = document.createElement("div");
				if (czyromantic()) img.style.transform = 'scaleX(-1)';
				img.appendChild(pieceSVG(pieceName));
                img.style.width = g_cellSize+'px';
                img.style.height = g_cellSize+'px';
                var divimg = document.createElement("div"); divimg.className = 'divimg';
                divimg.appendChild(img);

                $(divimg).draggable({ start: function (e, ui) {
                    if (g_selectedPiece === null) { return startmove(e,'-mousedrop');
                        //g_selectedPiece = this;
                        //var offset = $(this).closest('table').offset();
                        //g_startOffset = {
                        //    left: e.pageX - offset.left,
                        //    top: e.pageY - offset.top
                        //};
                    } else {
						dragstyle();
                        //return g_selectedPiece == this;
						//return g_selectedPiece == this && legalsquaresfrome(e).length > 0;
						return g_selectedPiece == this && prelegalsquaresfrome(e).length > 0;
                    }
                }});

                /*$(divimg).mousedown(function(e) {
					if (GameOver) { GameOverNotice(e); return; }
                    if (g_selectedPiece === null) { vibrate('selectedPiece');
                        var offset = $(this).closest('table').offset();
                        g_startOffset = {
                            left: e.pageX - offset.left,
                            top: e.pageY - offset.top
                        };
                        e.stopPropagation();
                        g_selectedPiece = this;
						g_selectedPiece.style.background = selectedPieceBackground(); //'rgba(7,7,7,0.5)';
                    } else if (g_selectedPiece === this) { return; // self click
						g_selectedPiece.style.background = 'none';
                        g_selectedPiece = null;
                    }
                });*/
				//divimg.onmousedown = startmove;
				
				$(divimg).mousedown(function(e)
				{
					if (g_enginethinking) return;
					if (e.which != 1) { e.preventDefault(); e.stopPropagation(); return false; }
                    if (g_selectedPiece === null) {
                    } else if (g_selectedPiece === this) { // self click
						g_selectedPiece.style.background = 'none';
                        g_selectedPiece = null;
						console.log('trafiony w czule miejsce kodu');
                    }
                });//this is needed to prevent a drag and drop bug with dragging the selected piece, but I don't know why
				

                $(td).empty().append(divimg);
            } else
            {
             td.innerHTML = '';
             var img = document.createElement('div');
			 img.appendChild(blankimage());
             img.style.width = g_cellSize + 'px';
             img.style.height = g_cellSize + 'px';
             td.appendChild(img);
            }
        }
    }
}

function RedrawBoard() {
    var div = $("#board")[0];

    var table = document.createElement("table"); table.id = 'chessboardtable';
    table.cellPadding = "0px";
    table.cellSpacing = "0px";
    $(table).addClass('no-highlight');
	table.style.backgroundColor = darksquarecolor();
	table.style.outline = '2px solid #ddd';

    var tbody = document.createElement("tbody");

    g_uiBoard = [];

    /*var dropPiece = function (e, ui) { dropmove(e); return;
        var endX = e.pageX - $(table).offset().left;
        var endY = e.pageY - $(table).offset().top;

        endX = Math.floor(endX / g_cellSize);
        endY = Math.floor(endY / g_cellSize);

        var startX = Math.floor(g_startOffset.left / g_cellSize);
        var startY = Math.floor(g_startOffset.top / g_cellSize);

        if (!g_playerWhite) {
            startY = 7 - startY;
            endY = 7 - endY;
            startX = 7 - startX;
            endX = 7 - endX;
        }

        var moves = GenerateValidMoves();
        var move = null;
	 if (!GameOver)
		{
		 var ruchy = new Array();
       	 for (var i = 0; i < moves.length; i++) {
	            if ((moves[i] & 0xFF) == MakeSquare(startY, startX) &&
       	         ((moves[i] >> 8) & 0xFF) == MakeSquare(endY, endX)){
              	  move = moves[i];
			  ruchy.push(move);
       	     }
	        }
		 if (ruchy.length > 1)
	        {
			 promotiondialog(ruchy[3],ruchy[2],ruchy[0],ruchy[1],e.pageX,e.pageY,g_cellSize);
			 return;
	        }
		}

        if (!g_playerWhite) {
            startY = 7 - startY;
            endY = 7 - endY;
            startX = 7 - startX;
            endX = 7 - endX;
        }

        g_selectedPiece.style.left = 0;
        g_selectedPiece.style.top = 0;
		dropmove(e); return;
    };*/

    for (y = 0; y < 8; ++y) {
        var tr = document.createElement("tr");

        for (x = 0; x < 8; ++x) {
            var td = document.createElement("td");
            td.style.width = g_cellSize + "px";
            td.style.height = g_cellSize + "px";
            tr.appendChild(td);
            g_uiBoard[y * 8 + x] = td;
        }

        tbody.appendChild(tr);
    }

    table.appendChild(tbody);

    //$('body').droppable({ drop: dropPiece });
	//$('body').droppable({ drop: dropmove });
	$('body').droppable({ drop: function(e) { dropstyle(); dropmove(e,'-mousedrop'); } });
    /*$(table).mousedown(function(e) {
        if (g_selectedPiece !== null) {
            dropPiece(e);
        }
    });*/
	table.onmousedown = startmove;
	document.body.onmousemove = function(e)
	{
		if (g_enginethinking) return;
		if (g_selectedPiece === null) prestartmove(e); else midmove(e);
		
		// to prevent dragging outside the board:
		if (g_selectedPiece === null) return;
		if (e.which != 1) return; // no dragging is taking place
		var SE = $(el('bottomczyjruch')).offset(), NW = $(el('chessboardtable')).offset();
		var margin = g_cellSize*0.2;
		var minX = NW.left - margin, minY = NW.top - margin, maxX = SE.left + margin, maxY = SE.top + margin;
		if (e.pageY > maxY || e.pageX > maxX || e.pageY < minY || e.pageX < minX)
		{
			if (g_selectedPiece.style.left == '' && g_selectedPiece.style.top == '' ) { console.log('multiple dragout event, doing nothing'); return; }
			galogg('left-board',(e.which==1) ? 'drag' : 'nodrag');
			cancelselected(); RedrawPieces(); g_stateline_decorate();
			var X = Math.floor(g_startOffset.left / g_cellSize); var Y = Math.floor(g_startOffset.top / g_cellSize);
			var square = uisquare(X,Y);
			g_selectedPiece = square.querySelector('.divimg');
			if (g_selectedPiece) g_selectedPiece.style.background = selectedPieceBackground(marklegalsquares(legalsquaresfrom(X,Y)));
			return;
		}
	}
	

    RedrawPieces();

    $(div).empty();
    div.appendChild(table);
	    
    applynoselect(div);
    
    g_changingFen = true;
    //document.getElementById("FenTextBox").value = GetFen();
    g_changingFen = false;
	
	var arplat = el('arplat');
	if (arplat) document.body.removeChild(arplat);
	document.body.appendChild(arrowsplatform());
	
	div.oncontextmenu = function(e){ return false; };

	el('l8').onclick = togglecontrols;
}

function promotiondialog(queen,knight,rook,bishop,x,y,size)
{
 var whitetomove = GetFen().indexOf(' w ') > -1;
 var flip = el('flipbox').checked;
 var qq = (whitetomove) ? svgmerida('Q') : svgmerida('q'); qq.style.verticalAlign = 'middle';
 var nn = (whitetomove) ? svgmerida('N') : svgmerida('n'); nn.style.verticalAlign = 'middle';
 var rr = (whitetomove) ? svgmerida('R') : svgmerida('r'); rr.style.verticalAlign = 'middle';
 var bb = (whitetomove) ? svgmerida('B') : svgmerida('b'); bb.style.verticalAlign = 'middle';
 var q = newel('fieldset'); q.appendChild(qq); q.setAttribute('onclick','makepromotion('+queen+');');
 var n = newel('fieldset'); n.appendChild(nn); n.setAttribute('onclick','makepromotion('+knight+');');
 var r = newel('fieldset'); r.appendChild(rr); r.setAttribute('onclick','makepromotion('+rook+');');
 var b = newel('fieldset'); b.appendChild(bb); b.setAttribute('onclick','makepromotion('+bishop+');');
 var aa = newel('div'); aa.appendChild(q); aa.appendChild(n);
 var zz = newel('div'); zz.appendChild(r); zz.appendChild(b);
 var pr = newel('div'); pr.id = 'promotionDialog'; pr.style.position = 'absolute'; pr.style.background = '#ddd';
 pr.style.border = 'thick solid #aaa'; pr.style.borderRadius = '4px';
 if ((whitetomove && !flip) || (!whitetomove && flip))
 {
  pr.appendChild(aa); pr.appendChild(zz); pr.style.left = (x-size/2) + 'px'; pr.style.top = (y-size/2) + 'px';
 }
 else
 {
  pr.appendChild(zz); pr.appendChild(aa); pr.style.left = (x-size/2) + 'px'; pr.style.top = (y-size*1.5) + 'px';
 }
 var bu = pr.querySelectorAll("fieldset"); for (var i=0; i < bu.length; i++)
 {
  bu[i].style.width = size + 'px'; bu[i].style.height = size + 'px'; bu[i].style.padding = '0';
  bu[i].style.display = 'inline-block'; bu[i].style.margin = '0'; bu[i].style.border = 'none';
 }
 var ov = newel('div'); ov.id = 'overlay'; ov.style.position = 'absolute'; ov.style.left = '0'; ov.style.top = '0';
 ov.style.width = window.innerWidth + 'px'; ov.style.height = window.innerHeight + 'px';
 ov.style.background = 'rgba(255,255,255,0.3)';
 ov.style.cursor = 'not-allowed';
 ov.setAttribute('onclick','makepromotion('+queen+'); return false;');
 document.body.appendChild(ov);
 document.body.appendChild(pr);
}

function clearlegalsquares()
{
    for (y = 0; y < 8; ++y) {
        for (x = 0; x < 8; ++x) {
            var td = g_uiBoard[y * 8 + x];
			td.style.boxShadow = 'none';
		}
	}
	// clears last move indication which is also done through boxShadow
}

function samesquaremoves(move)
{
	var moves = GenerateValidMoves();
	var ruchy = [];
	for (var i = 0; i < moves.length; i++)
	{
		if ((moves[i] & 0xFF) == (move & 0xFF) && ((moves[i] >> 8) & 0xFF) == ((move >> 8) & 0xFF)) ruchy.push(moves[i]);
	}
	return ruchy;
}

function playuimoveP(move,e)
{
	var ruchy = samesquaremoves(move);
	if (ruchy.length > 1)
	{
		var noresult = (mode & 128) == 128;
		if (!noresult && ((g_toMove && g_noBlackKing) || (g_toMove == 0 && g_noWhiteKing))) playuimove(ruchy[3]);
		else promotiondialog(ruchy[3],ruchy[2],ruchy[0],ruchy[1],e.pageX,e.pageY,g_cellSize);
	}
	else playuimove(move);
}
				 
function playuimove(move)
{
	actuiprelegalmove(move);
}

function actuiprelegalmove(move)
{
	clearhoverselected();
	var moves = GenerateValidMoves();
	var illegal = true;
	if (moves.length > 0) for (var i = moves.length-1; i>=0; i--) if (moves[i] == move) illegal = false;
	if (illegal)
	{
		// clear corrected squares but show legal squares
		clearlegalsquares(); marklegalsquares_from_selected();
		console.log('illegal move: ' + FormatMove(move) + ' kingtake: ' + FormatMove(g_kingtakes[move]));
		galogg('humanmove','prelegal');
		vibrate('prelegalIllegal');
		showkingtake(g_kingtakes[move]);
		return;
	}
	clearlegalsquares();
	if (GameOver) return;
	
	/*g_stateline_droptail();
	UpdatePgnTextBox(move);
	if (InitializeBackgroundEngine()) { g_backgroundEngine.postMessage(FormatMove(move)); }
	g_allMoves[g_allMoves.length] = move;
	MakeMove(move);
	UpdateFromMove(move);
	//g_selectedPiece.style.background = 'none'; g_selectedPiece = null;
	aftermove(move);*/
	g_stateline_makemove(move,'user');
	onUserMove(move);
}


function makepromotion(move)
{
	playuimove(move);
	document.body.removeChild(el('promotionDialog'));
	document.body.removeChild(el('overlay'));
}

function cancelselected()
{
	if (g_selectedPiece)
	{
		g_selectedPiece.style.background = 'none';
		g_selectedPiece = null;
	}
	clearlegalsquares();
}

function canceldrag()
{
	if (g_selectedPiece)
	{
		g_selectedPiece.style.left = 0;
		g_selectedPiece.style.top = 0;
	}
	cancelselected();
}

function EngineThinkingNotice(e)
{
	vibrate('EngineThinking');
	var a = newel('span'); a.innerHTML = EngineThinkingText(); a.id = 'enginethinkingnotice';
	a.style.position = 'fixed'; a.style.left = e.pageX + 'px'; a.style.top = e.pageY + 'px';
	a.style.background = 'white'; a.style.borderRadius = '5px'; a.style.border = '1px solid black';
	a.style.padding = '5px'; a.style.fontWeight = 'bold';
	document.body.appendChild(a);
	setTimeout("document.body.removeChild(el('enginethinkingnotice'));",700);
	galogg('humanmove','enginethinking');
}

function GameOverNotice(e)
{
	//canceldrag();
	if (g_inCheck)
	{
		if (!el('arplat').querySelector('.redarrow')) checkarrow(); // this has no effect anyway
	}
	vibrate('GameOver');
	var a = newel('span'); a.innerHTML = GameOverText(); a.id = 'gameovernotice';
	a.style.position = 'fixed'; a.style.left = e.pageX + 'px'; a.style.top = e.pageY + 'px';
	a.style.background = 'white'; a.style.borderRadius = '5px'; a.style.border = '1px solid black';
	a.style.padding = '5px'; a.style.fontWeight = 'bold';
	document.body.appendChild(a);
	setTimeout("document.body.removeChild(el('gameovernotice'));",700);
	galogg('humanmove','gameover');
}

function expandczyjruch()
{
	el('topczyjruch').style.transform = 'scale(2.5)';
	el('bottomczyjruch').style.transform = 'scale(2.5)';	
}
function shrinkczyjruch()
{
	el('topczyjruch').style.transform = 'scale(1.5)';
	el('bottomczyjruch').style.transform = 'scale(1.5)';
}

function WrongColorNotice(e)
{
	if (GameOver) { GameOverNotice(e); return; }
	vibrate('WrongColor');
	var whitetomove = GetFen().indexOf(' w ')>-1;
	var WrongColorText = (whitetomove) ? WhiteToMoveText() : BlackToMoveText();
	var a = newel('span'); a.innerHTML = WrongColorText; a.id = 'wrongcolornotice';
	a.style.position = 'fixed'; a.style.left = e.pageX + 'px'; a.style.top = e.pageY + 'px';
	a.style.background = 'white'; a.style.borderRadius = '5px'; a.style.border = '1px solid black';
	a.style.padding = '5px'; a.style.fontWeight = 'bold';
	document.body.appendChild(a);
	expandczyjruch();
	setTimeout("document.body.removeChild(el('wrongcolornotice')); shrinkczyjruch();",700);
	galogg('humanmove','wrongcolor');
}

function UnblockBoardSquares() { for (y = 0; y < 8; ++y) for (x = 0; x < 8; ++x) g_uiBoard[y * 8 + x].style.outline = 'none'; }

function BlockedChessmanNotice(e)
{
	if (GameOver) { GameOverNotice(e); return; }
	var sq = eventsquare(e);
	sq.style.outline = 'thick solid red';
	setTimeout(UnblockBoardSquares,700);
	vibrate('BlockedChessman');
	var a = newel('span'); a.innerHTML = BlockedChessmanText(); a.id = 'blockedchessmannotice';
	a.style.position = 'fixed'; a.style.left = e.pageX + 'px'; a.style.top = e.pageY + 'px';
	a.style.background = 'white'; a.style.borderRadius = '5px'; a.style.border = '1px solid black';
	a.style.padding = '5px'; a.style.fontWeight = 'bold';
	document.body.appendChild(a);
	setTimeout("document.body.removeChild(el('blockedchessmannotice'));",700);
	galogg('humanmove','blocked-chessman');
}


function uisquare(x,y) { return g_uiBoard[y * 8 + x]; }

function eventsquare(e)
{
	var table = el('chessboardtable');
	var tableleft = $(table).offset().left;
	var tabletop = $(table).offset().top;
	var endxx = ((e.pageX - tableleft) / g_cellSize);
	var endX = Math.floor(endxx);
	var endyy = ((e.pageY - tabletop) / g_cellSize);
	var endY = Math.floor(endyy);
	return uisquare(endX,endY);
}

function selecteventsquare(e)
{
	var table = el('chessboardtable');
	var tableleft = $(table).offset().left;
	var tabletop = $(table).offset().top;
	var endxx = ((e.pageX - tableleft) / g_cellSize);
	var endX = Math.floor(endxx);
	var endyy = ((e.pageY - tabletop) / g_cellSize);
	var endY = Math.floor(endyy);
	var square = uisquare(endX,endY);
	g_selectedPiece = square.querySelector('.divimg');
	var canmove = legalsquaresfrome(e).length > 0;
	if (g_selectedPiece) g_selectedPiece.style.background = selectedPieceBackground(canmove);
}

function whatoneventsquare(e)
{
	var table = el('chessboardtable');
	var tableleft = $(table).offset().left;
	var tabletop = $(table).offset().top;
	var endxx = ((e.pageX - tableleft) / g_cellSize);
	var endX = Math.floor(endxx);
	var endyy = ((e.pageY - tabletop) / g_cellSize);
	var endY = Math.floor(endyy);
	if (!g_playerWhite) { endY = 7 - endY; endX = 7 - endX; }
	//var poza = pozafromFEN_svgram(GetFen(),8,8);
	var poza = pozafromFENtokens(GetFen());
	var x2 = endX+1, y2 = 8-endY;
	var what = poza[squareIndex(x2,y2,8,8,false)];
	return what;
}

function dragstyle()
{
	var divimgs = el('chessboardtable').querySelectorAll('.divimg');
	var ile = divimgs.length;
	if (ile > 0) for (i=0; i < ile; i++) { divimgs[i].style.zIndex = '0'; }
	if (g_selectedPiece) { g_selectedPiece.style.zIndex = '1'; }
	if (g_selectedPiece)
	{
		var startX = Math.floor(g_startOffset.left / g_cellSize);
		var startY = Math.floor(g_startOffset.top / g_cellSize);
		uisquare(startX,startY).style.background = g_selectedPiece.style.background;
		g_selectedPiece.style.background = 'none';
	}
}
function dropstyle()
{
	var divimgs = el('chessboardtable').querySelectorAll('.divimg');
	var ile = divimgs.length;
	if (ile > 0) for (i=0; i < ile; i++) { divimgs[i].style.zIndex = '0'; }
}

/*
function isclearclick(e)
{
	var table = el('chessboardtable');
	var tableleft = $(table).offset().left;
	var tabletop = $(table).offset().top;
	var endxx = ((e.pageX - tableleft) / g_cellSize);
	var endX = Math.floor(endxx);
	var endyy = ((e.pageY - tabletop) / g_cellSize);
	var endY = Math.floor(endyy);
	return (Math.abs(endX+0.5-endxx) < 0.25 && Math.abs(endY+0.5-endyy) < 0.25);
}
*/

function startmove(e,medium)
{
	el('chessboardtable').style.cursor = 'auto';
	
	if (g_selectedPiece)
	{
		dropmove(e,medium);
		return;
	}
	if (g_selectedPiece === null)
	{
		if (isemptyareaclick(e)) { RedrawBoard(); return false; }
		if (g_enginethinking) { EngineThinkingNotice(e); return false; }
		if (GameOver) GameOverNotice(e);
		e.stopPropagation();
		if (GameOver >= 3) // not mate, not stalemate, so board becomes unresponsive
		{
			cancelselected();
			return false;
		}
		var what = whatoneventsquare(e);
		if (what == '_') return false;
		var whitetomove = GetFen().indexOf(' w ') > -1;
		var wrongcolor = (what.toUpperCase() == what) != whitetomove;
		if (wrongcolor) { WrongColorNotice(e); return false; }
		var offset = $(this).closest('table').offset();
		var offset = $(el('chessboardtable')).offset();
		g_startOffset = {
			left: e.pageX - offset.left,
			top: e.pageY - offset.top
		};
		selecteventsquare(e);
		var legalsquares = legalsquaresfrome(e);
		if (legalsquares.length > 0)
		{
			vibrate('selectedPiece');
			marklegalsquares(legalsquares);
			return true;
		}
		else
		{
			console.log('selected piece cannot move');
			var prelegalsquares = prelegalsquaresfrome(e);
			if (prelegalsquares.length == 0)
			{
				BlockedChessmanNotice(e);
				cancelselected();//setTimeout(cancelselected,500);
				return false;
			}
			PrelegalMovesFromEvent(e);
			var unique = true;
			for (var i=g_kingtakes2.length-1; i>=0; i--) if (g_kingtakes2[i] != g_kingtakes2[0]) unique = false;
			if (unique)
			{
				showkingtake(g_kingtakes2[0]);
				vibrate('prelegalIllegal');
				cancelselected();//setTimeout(cancelselected,1000);
				return false;
			}
			if (GameOver) vibrate('GameOver'); else vibrate('prelegalIllegal');
			console.log('illegal prelegal startmove');
			return true;
		}
	}
}

function midmove(e) // originally meant for dragging but used for click-click too
{	
	var table = el('chessboardtable');
	var tableleft = $(table).offset().left;
	var tabletop = $(table).offset().top;
	var endxx = ((e.pageX - tableleft) / g_cellSize);
	var endX = Math.floor(endxx);
	var endyy = ((e.pageY - tabletop) / g_cellSize);
	var endY = Math.floor(endyy);
	var startxx = g_startOffset.left / g_cellSize;
	var startX = Math.floor(startxx);
	var startyy = g_startOffset.top / g_cellSize;
	var startY = Math.floor(startyy);

	if (startX == endX && startY == endY)
	{
		if (Math.abs(endX+0.5-endxx) < 0.25 && Math.abs(endY+0.5-endyy) < 0.25) { hoverselectsquares([]); return };
	}

	function islegalmovefromto(move,startX,startY,endX,endY)
	{
		if (!g_playerWhite) { startY = 7 - startY; endY = 7 - endY; startX = 7 - startX; endX = 7 - endX; }
		return ((move & 0xFF) == MakeSquare(startY,startX) && ((move >> 8) & 0xFF) == MakeSquare(endY,endX));
	}
	function adjacentsquares()
	{
		var s = []; s.push( { x: endX, y: endY } );
		if (endY > 0) s.push( { x: endX, y: endY-1 } ); if (endY < 7) s.push( { x: endX, y: endY+1 } );
		if (endX > 0) { s.push( { x: endX-1, y: endY } ); if (endY > 0) s.push( { x: endX-1, y: endY-1 } ); if (endY < 7) s.push( { x: endX-1, y: endY+1 } ); }
		if (endX < 7) { s.push( { x: endX+1, y: endY } ); if (endY > 0) s.push( { x: endX+1, y: endY-1 } ); if (endY < 7) s.push( { x: endX+1, y: endY+1 } ); }
		return s;
	}
	var islegal = false;
	function adjacentlegalsquares()
	{
		var adj = adjacentsquares();
		//var moves = GenerateValidMoves();
		var moves = GeneratePrelegalMoves();
		var leg = [];
		for (var i=0; i < adj.length; i++)
		{
			var x = adj[i].x, y = adj[i].y;
			for (var m=0, ile=moves.length; m<ile; m++)
			{
				var move = moves[m];
				if (islegalmovefromto(move,startX,startY,x,y))
				{
					leg.push( { move: move, x: x, y: y } );
					if (x == endX && y == endY) islegal = true;
					m = ile;
				}
			}
		}
		return leg;
	}
	function closelegalsquares()
	{
		var radius = 0.7;
		var s = adjacentlegalsquares();
		var ret = [];
		for (var i=0; i < s.length; i++)
		{
			var x = s[i].x, y = s[i].y;
			if (Math.abs(x+0.5-endxx) < radius && Math.abs(y+0.5-endyy) < radius) ret.push(s[i]);
		}
		return ret;
	}
	var s = closelegalsquares(), ile = s.length;
	hoverselectsquares( (ile == 0) ? adjacentlegalsquares() : s  );
	
	if (s.length == 1 && !ismovelegal(s[0].move)) showkingtake(g_kingtakes[s[0].move]);
	if (s.length == 1 && ismovelegal(s[0].move)) clearredarrows();
}

function dropmove(e,medium)
{
	if (!g_selectedPiece)
	{
		console.log('dropmove without selectedPiece');
		g_stateline_refresh(); // for good measure
		return false; // this prevents a js error caused by pressing 0 to play a nullmove in the middle of dragging
	}
	else
	{
		g_selectedPiece.style.left = 0;
		g_selectedPiece.style.top = 0;
	}
	var medium = medium || ''; // '-mousedrop' '-secondclick' '-touchend'
	if (e.type == 'mousedown') medium = '-secondclick';
	var table = el('chessboardtable');
	var tableleft = $(table).offset().left;
	var tabletop = $(table).offset().top;
	var endxx = ((e.pageX - tableleft) / g_cellSize);
	var endX = Math.floor(endxx);
	var endyy = ((e.pageY - tabletop) / g_cellSize);
	var endY = Math.floor(endyy);
	var startxx = g_startOffset.left / g_cellSize;
	var startX = Math.floor(startxx);
	var startyy = g_startOffset.top / g_cellSize;
	var startY = Math.floor(startyy);

	if (startX == endX && startY == endY)
	{
		if (Math.abs(endX+0.5-endxx) < 0.25 && Math.abs(endY+0.5-endyy) < 0.25)
		{
			cancelselected();
			return;
		}
	}

	function islegalmovefromto(move,startX,startY,endX,endY)
	{
		if (!g_playerWhite) { startY = 7 - startY; endY = 7 - endY; startX = 7 - startX; endX = 7 - endX; }
		return ((move & 0xFF) == MakeSquare(startY,startX) && ((move >> 8) & 0xFF) == MakeSquare(endY,endX));
	}
	function adjacentsquares()
	{
		var s = []; s.push( { x: endX, y: endY } );
		if (endY > 0) s.push( { x: endX, y: endY-1 } ); if (endY < 7) s.push( { x: endX, y: endY+1 } );
		if (endX > 0) { s.push( { x: endX-1, y: endY } ); if (endY > 0) s.push( { x: endX-1, y: endY-1 } ); if (endY < 7) s.push( { x: endX-1, y: endY+1 } ); }
		if (endX < 7) { s.push( { x: endX+1, y: endY } ); if (endY > 0) s.push( { x: endX+1, y: endY-1 } ); if (endY < 7) s.push( { x: endX+1, y: endY+1 } ); }
		return s;
	}
	var islegal = false;
	function adjacentlegalsquares()
	{
		var adj = adjacentsquares();
		//var moves = GenerateValidMoves();
		var moves = GeneratePrelegalMoves();
		var leg = [];
		for (var i=0; i < adj.length; i++)
		{
			var x = adj[i].x, y = adj[i].y;
			for (var m=0, ile=moves.length; m<ile; m++)
			{
				var move = moves[m];
				if (islegalmovefromto(move,startX,startY,x,y))
				{
					leg.push( { move: move, x: x, y: y } );
					if (x == endX && y == endY) islegal = true;
					m = ile;
				}
			}
		}
		return leg;
	}
	function closelegalsquares()
	{
		var radius = 0.7;
		var s = adjacentlegalsquares();
		var ret = [];
		for (var i=0; i < s.length; i++)
		{
			var x = s[i].x, y = s[i].y;
			if (Math.abs(x+0.5-endxx) < radius && Math.abs(y+0.5-endyy) < radius) ret.push(s[i]);
		}
		return ret;
	}
	var s = closelegalsquares();
	var ile = s.length;
	if (ile == 1)
	{
		if (islegal) galogg('humanmove'+medium,'legal-clear');
		        else galogg('humanmove'+medium,'illegal-unicorrected-1');
		playuimoveP(s[0].move,e);
		return;
	}
	var multicorrectedstep = 'step1';
	
	var what = whatoneventsquare(e), whitetomove = GetFen().indexOf(' w ')>-1;
	if (what != '_' && ((what.toUpperCase() == what) == whitetomove) && medium != '-mousedrop')
	{
		// illegally clicked on another piece of user's color
		// stop looking for second level correction because it might be a change of mind
		cancelselected();
		//clearlegalsquares();
		//vibrate('illegalMove');
		rejectedmove(startX,startY,endX,endY,e,medium);
		return;
	}

	if (ile == 0)
	{
		s = adjacentlegalsquares();
		ile = s.length;
		if (ile == 1)
		{
			galogg('humanmove'+medium,'illegal-unicorrected-2');
			playuimoveP(s[0].move,e);
			return;			
		}
		if (ile == 0)
		{
			cancelselected();
			//clearlegalsquares();
			vibrate('illegalMove');
			rejectedmove(startX,startY,endX,endY,e,medium);
			return;
		}
		multicorrectedstep = 'step2';		
	}
	clearlegalsquares();
	marklegalsquares_from_selected();
		
	for (var i=0; i < s.length; i++)
	{
		var x = s[i].x, y = s[i].y;
		var thick = g_cellSize*0.4 + 'px';
		var color = ismovelegal(s[i].move) ? 'orange' : 'red';
		uisquare(x,y).style.boxShadow = '0 0 ' + thick + ' ' + '0 ' + color +' inset';
	}
	if (islegal) galogg('humanmove'+medium,'legal-multicorrected');
	        else galogg('humanmove'+medium,'illegal-multicorrected-'+multicorrectedstep);
}

function rejectedmove(startX,startY,endX,endY,e,medium)
{
	function selectpiece(X,Y,same,canmove)
	{
		var square = uisquare(X,Y);
		g_selectedPiece = square.querySelector('.divimg');
		if (g_selectedPiece) g_selectedPiece.style.background = selectedPieceBackground(canmove);
		if (same) return;
		var table = el('chessboardtable');
		var tableleft = $(table).offset().left;
		var tabletop = $(table).offset().top;
		g_startOffset.left = (e.pageX - tableleft);
		g_startOffset.top = (e.pageY - tabletop);
	}
	if (!g_playerWhite) { startY = 7 - startY; endY = 7 - endY; startX = 7 - startX; endX = 7 - endX; }
	//var poza = pozafromFEN_svgram(GetFen(),8,8);
	var poza = pozafromFENtokens(GetFen());
	var x1 = startX+1, y1 = 8-startY, x2 = endX+1, y2 = 8-endY;
	if (!g_playerWhite) { startY = 7 - startY; endY = 7 - endY; startX = 7 - startX; endX = 7 - endX; }
	var fromwhat = poza[squareIndex(x1,y1,8,8,false)];
	var towhat = poza[squareIndex(x2,y2,8,8,false)];
	var istoempty = (towhat == '_');
	var isfromwhite = (fromwhat.toUpperCase() == fromwhat);
	var istowhite = (!istoempty && (towhat.toUpperCase() == towhat));
	if (istoempty)
	{
		//selectpiece(startX,startY,'same',marklegalsquares(legalsquaresfrom(startX,startY)));
		galogg('humanmove'+medium,'illegal-rejected-empty');
		return;
	}	
	if (isfromwhite == istowhite)
	{
		canceldrag(); cancelselected(); // cancel everything and startmove from scratch
		if (medium == '-mousedrop')
		{
			galogg('humanmove'+medium,'illegal-rejected');
			return;
		}
		galogg('humanmove'+medium,'illegal-changeofmind');
		startmove(e);
		return;
		//selectpiece(endX,endY,null,marklegalsquares(legalsquaresfrom(endX,endY)));
		//galogg('humanmove'+medium,'illegal-changeofmind');
		//return;
	}
	selectpiece(startX,startY,'same',marklegalsquares(legalsquaresfrom(startX,startY)));
	galogg('humanmove'+medium,'illegal-rejected');
}

function prelegalsquaresfrom(startX,startY)
{
	var moves = GeneratePrelegalMoves(), ile = moves.length;
	if (ile == 0) return [];
	var s = [];
	if (!g_playerWhite) { startY = 7 - startY; startX = 7 - startX; }
	for (i=0; i < ile; i++)
	{
		var move = moves[i];
		var fromX = (move & 0xF) - 4;
		var fromY = ((move >> 4) & 0xF) - 2;
		var toX = ((move >> 8) & 0xF) - 4;
		var toY = ((move >> 12) & 0xF) - 2;	
		if (fromX == startX && fromY == startY)
		{
			if (!g_playerWhite) { toY = 7 - toY; toX = 7 - toX; }
			s.push( uisquare(toX,toY) );
		}
	}
	return s;
}

function prelegalsquaresfrome(e)
{
	var table = el('chessboardtable');
	var tableleft = $(table).offset().left;
	var tabletop = $(table).offset().top;
	var eX = Math.floor((e.pageX - tableleft) / g_cellSize);
	var eY = Math.floor((e.pageY - tabletop) / g_cellSize);
	return prelegalsquaresfrom(eX,eY);
}

function legalsquaresfrom(startX,startY)
{
	var moves = GenerateValidMoves(), ile = moves.length;
	if (ile == 0) return [];
	var s = [];
	if (!g_playerWhite) { startY = 7 - startY; startX = 7 - startX; }
	for (i=0; i < ile; i++)
	{
		var move = moves[i];
		var fromX = (move & 0xF) - 4;
		var fromY = ((move >> 4) & 0xF) - 2;
		var toX = ((move >> 8) & 0xF) - 4;
		var toY = ((move >> 12) & 0xF) - 2;	
		if (fromX == startX && fromY == startY)
		{
			if (!g_playerWhite) { toY = 7 - toY; toX = 7 - toX; }
			s.push( uisquare(toX,toY) );
		}
	}
	return s;
}

function legalsquaresfrome(e)
{
	var table = el('chessboardtable');
	var tableleft = $(table).offset().left;
	var tabletop = $(table).offset().top;
	var eX = Math.floor((e.pageX - tableleft) / g_cellSize);
	var eY = Math.floor((e.pageY - tabletop) / g_cellSize);
	return legalsquaresfrom(eX,eY);
}

function marklegalsquares(s)
{
	var ile = s.length;
	if (ile == 0) return false;
	for (var i=0; i < ile; i++)
	{
		var a = s[i];
		var width = g_cellSize * 0.7 + 'px'; //var width = g_cellSize * 0.4 + 'px';
		var color = 'rgb(0,200,200,0.5)';
		a.style.boxShadow = 'inset 0 0 '+width+' '+color; //rgb(0,255,255,0.5)';
	}
	return true;
}

function marklegalsquares_from_selected()
{
	if (g_selectedPiece)
	{
		var startxx = g_startOffset.left / g_cellSize;
		var startX = Math.floor(startxx);
		var startyy = g_startOffset.top / g_cellSize;
		var startY = Math.floor(startyy);	
		marklegalsquares(legalsquaresfrom(startX,startY));
	}
}

// overwriting garbochess.js functions so that they store the kingtake move in g_kingtake
// later I added correction to take care of nonexistent kings
var g_kingchecked = null;
var g_kingtake = null;
function ExposesCheck(from, kingPos){
	if (!kingPos) return false;
    var index = kingPos - from + 128;
    // If a queen can't reach it, nobody can!
    if ((g_vectorDelta[index].pieceMask[0] & (1 << (pieceQueen))) != 0) {
        var delta = g_vectorDelta[index].delta;
        var pos = kingPos + delta;
        while (g_board[pos] == 0) pos += delta;
        
        var piece = g_board[pos];
        if (((piece & (g_board[kingPos] ^ 0x18)) & 0x18) == 0)
            return false;

        // Now see if the piece can actually attack the king
        var backwardIndex = pos - kingPos + 128;
		g_kingtake = GenerateMove(pos,kingPos); // MY ONE LINE MODIFICATION
        return (g_vectorDelta[backwardIndex].pieceMask[(piece >> 3) & 1] & (1 << (piece & 0x7))) != 0;
    }
    return false;
}
function IsSquareAttackable(target, color)
{
	// Attackable by pawns?
	var inc = color ? -16 : 16;
	var pawn = (color ? colorWhite : colorBlack) | 1;
	if (g_board[target - (inc - 1)] == pawn)
	{
		g_kingtake = GenerateMove(target - (inc - 1),target); // 1 OF 3 LINES I ADDED
		return true;
	}
	if (g_board[target - (inc + 1)] == pawn)
	{
		g_kingtake = GenerateMove(target - (inc + 1),target); // 2 OF 3 LINES I ADDED
		return true;
	}
	
	// Attackable by pieces?
	for (var i = 2; i <= 6; i++) {
        var index = (color | i) << 4;
        var square = g_pieceList[index];
		while (square != 0) {
			if (IsSquareAttackableFrom(target, square))
			{
				g_kingtake = GenerateMove(square,target); // 3 of 3 LINES I ADDED
				return true;
			}
			square = g_pieceList[++index];
		}
    }
    return false;
}
function IsSquareAttackableFrom(target, from)
{
	if (!target) return false; // to take care of nonexistent kings
	var skad = from;
    var index = from - target + 128;
    var piece = g_board[from];
    if (g_vectorDelta[index].pieceMask[(piece >> 3) & 1] & (1 << (piece & 0x7))) {
        // Yes, this square is pseudo-attackable.  Now, check for real attack
		var inc = g_vectorDelta[index].delta;
        do {
			from += inc;
			if (from == target)
			{
				g_kingtake = GenerateMove(skad,target);
				return true;
			}
		} while (g_board[from] == 0);
    }
    
    return false;
}
g_kingtakes = []; // indexed by prelegal illegal moves
g_kingtakes2 = []; // a normal array of kingtakes with no prelegals
function GeneratePrelegalMoves() {
    var moveList = new Array();
    var allMoves = new Array();
    GenerateCaptureMoves(allMoves, null);
    GenerateAllMoves(allMoves);
	g_kingtakes = []; g_kingtakes2 = [];
    for (var i = allMoves.length - 1; i >= 0; i--)
	{
        if (MakeMove(allMoves[i]))
		{
            moveList[moveList.length] = allMoves[i];
            UnmakeMove(allMoves[i]);
        }
		else
		{
			g_kingtakes[allMoves[i]] = g_kingtake;
			g_kingtakes2.push(g_kingtake);
		}
    }
    //return moveList; // legal moves
	return allMoves; // prelegal moves including legal moves
}

function PrelegalMovesFromEvent(e) // returns always null, sets g_kingtake2
{
	var table = el('chessboardtable');
	var tableleft = $(table).offset().left;
	var tabletop = $(table).offset().top;
	var endxx = ((e.pageX - tableleft) / g_cellSize);
	var endX = Math.floor(endxx);
	var endyy = ((e.pageY - tabletop) / g_cellSize);
	var endY = Math.floor(endyy);
	if (!g_playerWhite) { endY = 7 - endY; endX = 7 - endX; }

    var moveList = new Array();
    var allMoves = new Array();
    GenerateCaptureMoves(allMoves, null);
    GenerateAllMoves(allMoves);
	g_kingtakes = []; g_kingtakes2 = [];
    for (var i = allMoves.length - 1; i >= 0; i--)
	{
		var move = allMoves[i];
		var fromX = (move & 0xF) - 4;
		var fromY = ((move >> 4) & 0xF) - 2;
		if (fromX == endX && fromY == endY)
		{	
			if (MakeMove(allMoves[i]))
			{
				moveList[moveList.length] = allMoves[i];
				UnmakeMove(allMoves[i]);
			}
			else
			{
					g_kingtakes[allMoves[i]] = g_kingtake;
					g_kingtakes2.push(g_kingtake);
			}
		}
    }
	return null;
}

function arrowsplatform()
{
	var board = el('board'), x = $(board).offset().left, y = $(board).offset().top;
	var width = 8, height = 8, size = g_cellSize;
	var svg = svgelement('svg'); svg.setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:xlink", "http://www.w3.org/1999/xlink");
	svg.id = 'arplat';
	svg.setAttribute('width', size*width); svg.setAttribute('height', size*height);
	svg.setAttribute('viewBox', '0 0 '+size*width+' '+size*height);
	svg.style.position = 'absolute'; svg.style.left = x + 'px'; svg.style.top = y + 'px';
	svg.style.pointerEvents = 'none';
	//svg.style.outline = '2px solid #ddd'; this outline does not correspond with a large board's outline
	return svg;
}

function clearhoverselected()
{
	clearlegalsquares();
	marklegalsquares_from_selected();
}

function ismovelegal(move)
{
	var moves = GenerateValidMoves();
	if (moves.length == 0) return false;
	for (var i=moves.length-1; i>=0; i--) if (moves[i]==move) return true;
	return false;
}

function hoverselectsquares(s)
{
	clearhoverselected();
	var ile = s.length; if (ile == 0) return;
	function applystyle(sq,color)
	{
		var thick = g_cellSize*0.4 + 'px';
		sq.style.boxShadow = '0 0 ' + thick + ' ' + '0 ' + color +' inset';
	}
	if (ile == 1)
	{
		if (ismovelegal(s[0].move)) applystyle(uisquare(s[0].x,s[0].y),'green');
		                       else applystyle(uisquare(s[0].x,s[0].y),'red');
	}
	else for (var i=ile-1; i>=0; i--)
	{
		if (ismovelegal(s[i].move)) applystyle(uisquare(s[i].x,s[i].y),'orange');
		                       else applystyle(uisquare(s[i].x,s[i].y),'red');
	}
}

var g_prestart_boxShadow = 'none';

function prestartmove(e)
{
	if (GameOver) if (GameOver >= 3) return;
	var canmove = (legalsquaresfrome(e).length > 0);
	el('chessboardtable').style.cursor = (canmove) ? 'pointer' : 'auto';
	
	var thick = g_cellSize*0.4 + 'px';
	var thick = g_cellSize*0.8 + 'px';
	var color = 'rgb(0,255,255,0.5)';
	var prestart_boxShadow = '0 0 ' + thick + ' ' + '0 ' + color +' inset'
	for (var y = 0; y < 8; ++y) for (var x = 0; x < 8; ++x)
	{
		var td = g_uiBoard[y * 8 + x];
		if (td.style.boxShadow == g_prestart_boxShadow) td.style.boxShadow = 'none';
	}
	if (canmove)
	{
		eventsquare(e).style.boxShadow = prestart_boxShadow;
		g_prestart_boxShadow = eventsquare(e).style.boxShadow;
	}
}


function whatonuisquare(endX,endY)
{
	//var poza = pozafromFEN_svgram(GetFen(),8,8);
	var poza = pozafromFENtokens(GetFen());
	if (!g_playerWhite) { endY = 7 - endY; endX = 7 - endX; }
	var x2 = endX+1, y2 = 8-endY;
	return poza[squareIndex(x2,y2,8,8,false)];
}

function closesquares(e)
{
	var table = el('chessboardtable');
	var tableleft = $(table).offset().left;
	var tabletop = $(table).offset().top;
	var endxx = ((e.pageX - tableleft) / g_cellSize);
	var endX = Math.floor(endxx);
	var endyy = ((e.pageY - tabletop) / g_cellSize);
	var endY = Math.floor(endyy);
	
	var s = []; s.push( { x: endX, y: endY } );
	if (endY > 0) s.push( { x: endX, y: endY-1 } ); if (endY < 7) s.push( { x: endX, y: endY+1 } );
	if (endX > 0) { s.push( { x: endX-1, y: endY } ); if (endY > 0) s.push( { x: endX-1, y: endY-1 } ); if (endY < 7) s.push( { x: endX-1, y: endY+1 } ); }
	if (endX < 7) { s.push( { x: endX+1, y: endY } ); if (endY > 0) s.push( { x: endX+1, y: endY-1 } ); if (endY < 7) s.push( { x: endX+1, y: endY+1 } ); }
	
	var radius = 0.7;
	var ret = [];
	for (var i=0; i < s.length; i++)
	{
		var x = s[i].x, y = s[i].y;
		if (Math.abs(x+0.5-endxx) < radius && Math.abs(y+0.5-endyy) < radius) ret.push(s[i]);
	}
	return ret;
}

function isemptyareaclick(e)
{
	var s = closesquares(e);
	var ile = s.length; if (ile == 0) return false;
	for (var i=0; i<ile; i++)
	{
		var what = whatonuisquare(s[i].x,s[i].y);
		if (what != '_') return false;
	}
	return true;
}

/* same as the overwritten appendix to garbochess.js
function GenerateValidMoves() {
    var moveList = new Array();
    var allMoves = new Array();
    GenerateCaptureMoves(allMoves, null);
    GenerateAllMoves(allMoves);

	if (g_toMove == 0 && g_noBlackKing) return allMoves.reverse();
	if (g_toMove && g_noWhiteKing) return allMoves.reverse();
    
    for (var i = allMoves.length - 1; i >= 0; i--) {
        if (MakeMove(allMoves[i])) {
            moveList[moveList.length] = allMoves[i];
            UnmakeMove(allMoves[i]);
        }
    }
    
    return moveList;
}
*/
function svgmerida(what)
{
 var div = document.createElement('div');
 if (what == 'K') div.innerHTML = '<svg xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" height="100%" width="100%" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" viewBox="0 0 2048 2048"> <path style="color:black;" d="m524 583q190 48 504 48 308 0 497-46l25 147q-201 53-526 53-327 0-525-54l25-148zm-61-61l-33 187q-1 0-3 4-5 7-28 18t-55 40q-44 37-68 60t-45 50q-61 84-68 203-10 115 93 229 104 114 281 107 66-4 155-32 29-12 59-23t61-24q16-8 28-16-5 21-5 42 0 78 55 133t134 56q78 0 133-55t55-133q0-16-4-42 14 9 27 15 46 20 121 47 86 29 155 33 177 8 280-107 101-114 94-229-8-119-69-203-20-27-45-50t-67-60q-33-28-56-39t-27-19q-1-2-2-3t-1-2l-32-188 66-247q-50-45-224-74t-402-29q-232 0-408 30t-221 77l66 244zm1081-117l-30 115q-198 44-490 44-291 0-489-44l-32-116q191 56 522 56 329 0 519-55zm26-94q-193 78-542 78-362 0-548-80 176-70 545-70 176 0 321 19t224 53zm-581 542q-1 39-3 77t-15 86q-41 134-124 216-43 42-132 78-102 40-193 40-158 0-234-113-43-60-43-150 0-98 48-161 29-37 74-74t84-67q175 63 538 68zm35 186q7 28 12 39 10 39 23 66 6 17 17 39t25 52q8 17 17 41t18 49q8 20 8 43 0 49-35 83t-85 35q-119 0-119-119 0-23 8-43 22-65 34-90 13-29 24-51t19-40q13-33 22-66 2-6 12-38zm34-186q172-2 315-20t224-47q38 30 83 66t75 75q48 61 48 161 0 90-43 150-77 112-234 112-94 0-193-39-87-35-132-78-84-84-124-216-14-47-16-85t-3-79zm1 706h-71v131h-83q-34 0-34 33v1q0 33 34 33h83v85q0 35 36 35 35 0 35-35v-85h86q33 0 33-33v-1q0-33-33-33l-85-1-1-130z" fill-rule="nonzero" transform="translate(0,2048) scale(1,-1)" display="block" fill="#000"/> <g fill-rule="nonzero" fill="#fff"> <path style="color:black;" d="m524 1465q190-48 504-48 308 0 497 46l25-147q-201-53-526-53-327 0-525 54l25 148z" display="block"/> <path style="color:black;" d="m1544 1643-30-115q-198-44-490-44-291 0-489 44l-32 116q191-56 522-56 329 0 519 55z" display="block"/> <path style="color:black;" d="m1570 1737q-193-78-542-78-362 0-548 80 176 70 545 70 176 0 321-19t224-53z" display="block"/> <path style="color:black;" d="m989 1195q-1-39-3-77t-15-86q-41-134-124-216-43-42-132-78-102-40-193-40-158 0-234 113-43 60-43 150 0 98 48 161 29 37 74 74t84 67q175-63 538-68z" display="block"/> <path style="color:black;" d="m1024 1009q7-28 12-39 10-39 23-66 6-17 17-39t25-52q8-17 17-41t18-49q8-20 8-43 0-49-35-83t-85-35q-119 0-119 119 0 23 8 43 22 65 34 90 13 29 24 51t19 40q13 33 22 66 2 6 12 38z" display="block"/> <path style="color:black;" d="m1058 1195q172 2 315 20t224 47q38-30 83-66t75-75q48-61 48-161 0-90-43-150-77-112-234-112-94 0-193 39-87 35-132 78-84 84-124 216-14 47-16 85t-3 79z" display="block"/> </g> </svg> ';
 if (what == 'Q') div.innerHTML = '<svg xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" height="100%" width="100%" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" viewBox="0 0 2048 2048"> <path style="color:black;" d="m1024 1621q-57 0-96 40t-40 97q0 56 39 96t97 40q56 0 96-40t41-96q0-57-40-97t-97-40zm0 70q67 0 67 67 0 66-67 66-66 0-66-66 0-67 66-67zm509-1007q-193 49-505 49-320 0-511-50l15-96q195 46 496 46 299 0 488-45l17 96zm25 60 65 115q-48-19-98-19-134 0-214 109-60-50-134-50-96 0-153 75-64-70-153-70-72 0-132 49-84-107-217-107-51 0-101 19l70-120q194 56 533 56 345 0 534-57zm-448 239-85 489-85-483q3 2 14 10 23 45 70 45 51 0 66-45 6-6 20-16zm277-19v463l-165-454q19 7 32 18 20 25 54 25 40 0 64-35 3-4 7-8t8-9zm-562 14-164 449v-457q3 4 9 10 20 42 66 42 38 0 62-32 27-12 27-12zm-254-56-215 371 55-338q57-40 111-40 21 0 49 7zm903-5q23-7 51-7 61 0 114 38l55 346-220-377zm60-506-30 113q-196 43-480 43-281 0-479-43l-31-114q186 56 511 56 317 0 509-55zm106-128q-49-43-220-72t-394-29q-227 0-399 30t-217 75l63 240-28 157-88 153-85 622 49 19 274-462 6 550 68 12 209-553 112 595h69l112-593 207 551 69-12 6-550 275 463 47-22-83-619-89-153-28-159 65-243zm-81 36q-183 76-531 76-355 0-537-78 175-69 534-69 172 0 314 19t220 52zm-955 1252q-57 0-97 39t-40 97q0 56 40 96t97 40q56 0 96-40t40-96q0-57-40-96t-96-40zm0 70q66 0 66 66t-66 66q-67 0-67-66t67-66zm840 0q67 0 67 66t-67 66q-66 0-66-66t66-66zm0-70q-57 0-96 39t-40 97q0 56 39 96t97 40q57 0 97-40t40-96q0-57-40-96t-97-40zm-1208-110q-57 0-96 39t-40 97q0 56 39 96t97 41q57 0 97-40t40-97-40-96-97-40zm0 70q67 0 67 66 0 67-67 67-66 0-66-67 0-66 66-66zm1578 0q66 0 66 66 0 67-66 67-67 0-67-67 0-66 67-66zm0-70q-57 0-97 39t-40 97q0 56 40 96t97 41q56 0 96-40t40-97-40-96-96-40z" fill-rule="nonzero" transform="translate(0,2048) scale(1,-1)" display="block" fill="#000"/> <g fill-rule="nonzero" fill="#fff"> <path style="color:black;" d="m1024 357q67 0 67-67 0-66-67-66-66 0-66 66 0 67 66 67z" display="block"/> <path style="color:black;" d="m1533 1364q-193-49-505-49-320 0-511 50l15 96q195-46 496-46 299 0 488 45l17-96z" display="block"/> <path style="color:black;" d="m1558 1304 65-115q-48 19-98 19-134 0-214-109-60 50-134 50-96 0-153-75-64 70-153 70-72 0-132-49-84 107-217 107-51 0-101-19l70 120q194-56 533-56 345 0 534 57z" display="block"/> <path style="color:black;" d="m1110 1065-85-489-85 483q3-2 14-10 23-45 70-45 51 0 66 45 6 6 20 16z" display="block"/> <path style="color:black;" d="m1387 1084v-463l-165 454q19-7 32-18 20-25 54-25 40 0 64 35 3 4 7 8t8 9z" display="block"/> <path style="color:black;" d="m825 1070-164-449v457q3-4 9-10 20-42 66-42 38 0 62 32 27 12 27 12z" display="block"/> <path style="color:black;" d="m571 1126-215-371 55 338q57 40 111 40 21 0 49-7z" display="block"/> <path style="color:black;" d="m1474 1131q23 7 51 7 61 0 114-38l55-346-220 377z" display="block"/> <path style="color:black;" d="m1534 1637-30-113q-196-43-480-43-281 0-479 43l-31 114q186-56 511-56 317 0 509 55z" display="block"/> <path style="color:black;" d="m1559 1729q-183-76-531-76-355 0-537 78 175 69 534 69 172 0 314-19t220-52z" display="block"/> <path style="color:black;" d="m604 407q66 0 66-66t-66-66q-67 0-67 66t67 66z" display="block"/> <path style="color:black;" d="m1444 407q67 0 67-66t-67-66q-66 0-66 66t66 66z" display="block"/> <path style="color:black;" d="m236 517q67 0 67-66 0-67-67-67-66 0-66 67 0 66 66 66z" display="block"/> <path style="color:black;" d="m1814 517q66 0 66-66 0-67-66-67-67 0-67 67 0 66 67 66z" display="block"/> </g> </svg> ';
 if (what == 'R') div.innerHTML = '<svg xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" height="100%" width="100%" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" viewBox="0 0 2048 2048"> <path style="color:black;" d="m1161 1706h170v137h274v-375l-222-171v-478l170-170v-205h153v-239h-1365v239h153v205l171 170v478l-222 171v375h273v-137h171v137h274v-137zm478-1330h-1230v-103h1230v103zm-155 204h-920v-136h920v136zm-170 717h-580v-478h580v478zm222 239v239h-137v-137h-308v137h-135v-137h-307v137h-137v-239h1024zm-77-887l-103 102h-663l-105-102h871zm-110 716l127 103h-904l128-103h649z" fill-rule="nonzero" transform="translate(0,2048) scale(1,-1)" display="block" fill="#000"/> <g fill-rule="nonzero" fill="#fff"> <path style="color:black;" d="m1639 1672h-1230v103h1230v-103z" display="block"/> <path style="color:black;" d="m1484 1468h-920v136h920v-136z" display="block"/> <path style="color:black;" d="m1314 751h-580v478h580v-478z" display="block"/> <path style="color:black;" d="m1536 512v-239h-137v137h-308v-137h-135v137h-307v-137h-137v239h1024z" display="block"/> </g> <path style="color:black;" d="m1459 1399-103-102h-663l-105 102h871z" fill-rule="nonzero" display="block" fill="#fff"/> <path style="color:black;" d="m1349 683 127-103h-904l128 103h649z" fill-rule="nonzero" display="block" fill="#fff"/> </svg> ';
 if (what == 'B') div.innerHTML = '<svg xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" height="100%" width="100%" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" viewBox="0 0 2048 2048"> <path style="color:black;" d="m1024 1166q97 0 186 15 98-35 142-109 38-64 38-141 0-46-23-101t-75-101q-59-49-129-107t-139-137q-70 78-140 136t-128 108q-53 45-75 100t-23 102q0 77 37 141 43 74 143 109 88-15 186-15z" fill-rule="nonzero" display="block" fill="#fff"/> <path style="color:black;" d="m988 1138l-83 1q-34 0-34 34t34 34h83v86q0 35 36 35 35 0 35-35v-86h86q33 0 33-34t-33-34h-86v-82q0-36-35-36-36 0-36 36v81zm36-578q68 0 132 11t125 28q-117 31-257 31-142 0-257-31 58-16 123-27t134-12zm0 1089q68 0 68 68t-68 68-68-68 68-68zm0-949q117 0 225-23l-48 123q-88 14-177 14-91 0-178-14l-48-123q107 23 226 23zm0 182q97 0 186-15 98 35 142 109 38 64 38 141 0 46-23 101t-75 101q-59 49-129 107t-139 137q-70-78-140-136t-128-108q-53-45-75-100t-23-102q0-77 37-141 43-74 143-109 88 15 186 15zm-36-377h-32q-56-90-107-113-24-12-53-22t-67-10q-7 0-107 16-48 7-76 15t-44 10q-57 7-133-6-46-8-86-29l48-78q12 12 31 15t36 8q42 7 80 2 13-3 51-7t101-15q75-11 102-11 105 0 161 40 34 26 64 72t31 113zm36-113q-14-57-34-79t-53-46q-36-25-85-42t-109-9l-281 39q-17 2-30 0t-26-2q-21 0-53-9t-51-28l-97 159q18 20 32 28t33 17q58 27 124 33 28 2 55 1t56 2q54-9 108-16t110-16q60 0 81 12 11 6 35 22t48 47q-53 6-108 20t-97 31l104 258q-78 45-109 72t-49 64q-26 46-33 89t-7 77q1 60 28 132t104 130q63 48 123 99t119 119q-74 38-74 121 0 56 39 96t97 40q56 0 96-40t40-96q0-82-74-121 58-68 117-119t125-99q75-57 102-129t29-133q0-34-7-77t-32-89q-20-36-50-63t-108-73l104-258q-44-16-99-30t-106-21q23-31 47-47t36-22q21-12 81-12 54 8 108 15t110 17q27-3 54-2t57-1q64-6 124-33 18-9 32-17t33-28l-98-159q-18 18-50 27t-53 10q-13 0-26 2t-31 0l-280-39q-60-9-111 8t-85 45q-33 27-52 46t-33 77zm35 113q0-66 30-112t66-73q55-40 161-40 26 0 102 11 62 10 100 14t51 8q38 5 80-2 16-4 35-7t33-16l48 78q-41 21-87 29-76 13-133 6-17-2-44-10t-75-15q-101-16-107-16-39 0-68 10t-52 22q-53 24-108 113h-32z" fill-rule="nonzero" transform="translate(0,2048) scale(1,-1)" display="block" fill="#000"/> <g fill-rule="nonzero" fill="#fff"> <path style="color:black;" d="m1024 1488q68 0 132-11t125-28q-117-31-257-31-142 0-257 31 58 16 123 27t134 12z" display="block"/> <path style="color:black;" d="m1024 399q68 0 68-68t-68-68-68 68 68 68z" display="block"/> <path style="color:black;" d="m1024 1348q117 0 225 23l-48-123q-88-14-177-14-91 0-178 14l-48 123q107-23 226-23z" display="block"/> <path style="color:black;" d="m988 1543h-32q-56 90-107 113-24 12-53 22t-67 10q-7 0-107-16-48-7-76-15t-44-10q-57-7-133 6-46 8-86 29l48 78q12-12 31-15t36-8q42-7 80-2 13 3 51 7t101 15q75 11 102 11 105 0 161-40 34-26 64-72t31-113z" display="block"/> <path style="color:black;" d="m1059 1543q0 66 30 112t66 73q55 40 161 40 26 0 102-11 62-10 100-14t51-8q38-5 80 2 16 4 35 7t33 16l48-78q-41-21-87-29-76-13-133-6-17 2-44 10t-75 15q-101 16-107 16-39 0-68-10t-52-22q-53-24-108-113h-32z" display="block"/> </g> </svg> ';
 if (what == 'N') div.innerHTML = '<svg xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" height="100%" width="100%" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" viewBox="0 0 2048 2048"> <path style="color:black;" d="m1756 1774q-1 0 2-54t3-118q1-125 0-258t-36-267q-34-128-75-218t-89-157q-72-108-196-183t-259-98q9 49 8 96-2 36-34 36-37 0-33-36 3-132-94-226-76 80-82 186-2 35-36 31-32-1-32-37 0 0 2-8-41 13-86 37-29 20-47-10t16-47q43-22 65-33-86-88-196-142 12 139 76 263 17 26-8 46-28 22-47-7-7-10-20-36-21 21-28 31-7 9-24 40t-26 51q-9 25-8 40t3 35q-9 59-40 111t-81 127q-47 72-72 108t-38 92q-9 34 0 82t39 76q46 47 89 43 14 0 37-11t37-42q26-57 43-57 25 0 27 28 0 6-16 50-9 20-24 42-19 26-17 22 16 57 53 20 11-11 25-41t44-81q35-59 75-96t71-60q18-13 44-30t70-35q35-14 77-34t76-53q46-45 71-111 13-37 10-91-9-34 34-34 32 0 36 33 0 113-64 206 21 64 11 140-9 61-42 130t-136 155q-207 172-196 354 0 0 170 0t321 0h547z" fill-rule="nonzero" display="block" fill="#fff"/> <path style="color:black;" d="m697 1359q13-20 10-42-10-64-71-54-18 3-29 12-4-5-12-22-11-32-42-22-31 12-24 45 45 115 163 158 34 10 45-20 12-32-18-44-6-3-11-5t-11-6zm-297-477q-29-18-35-48 1-33-31-36-35-4-36 30 4 66 59 105 26 21 48-4 22-27-5-47zm679 815q157-10 291-81t228-182q66-78 124-188t94-233q40-143 50-300t11-292v-218t-155 0-404 0h-671q-9 0-9 49t7 79q4 24 19 68t50 107q16 32 76 93t138 143q45 46 70 116t22 127q-37-30-81-49-212-76-307-220-7-9-45-81-20-38-34-52-19-19-55-21-56-3-87 54-42-12-75-10-56 21-81 45-51 51-66 102t-16 110q0 84 104 222 122 159 130 242 0 36 7 81 6 31 25 60 13 20 17 27t17 23q9 12 15 18t15 18q11 13 28 30-53 146-43 301 199-71 334-223 33 113 130 183 80-56 127-148zm677-1423q-1 0 2 54t3 118q1 125 0 258t-36 267q-34 128-75 218t-89 157q-72 108-196 183t-259 98q9-49 8-96-2-36-34-36-37 0-33 36 3 132-94 226-76-80-82-186-2-35-36-31-32 1-32 37 0 0 2 8-41-13-86-37-29-20-47 10t16 47q43 22 65 33-86 88-196 142 12-139 76-263 17-26-8-46-28-22-47 7-7 10-20 36-21-21-28-31-7-9-24-40t-26-51q-9-25-8-40t3-35q-9-59-40-111t-81-127q-47-72-72-108t-38-92q-9-34 0-82t39-76q46-47 89-43 14 0 37 11t37 42q26 57 43 57 25 0 27-28 0-6-16-50-9-20-24-42-19-26-17-22 16-57 53-20 11 11 25 41t44 81q35 59 75 96t71 60q18 13 44 30t70 35q35 14 77 34t76 53q46 45 71 111 13 37 10 91-9 34 34 34 32 0 36-33 0-113-64-206 21-64 11-140-9-61-42-130t-136-155q-207-172-196-354 0 0 170 0t321 0h547z" fill-rule="nonzero" transform="translate(0,2048) scale(1,-1)" display="block" fill="#000"/> </svg> ';
 if (what == 'P') div.innerHTML = '<svg xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" height="100%" width="100%" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" viewBox="0 0 2048 2048"> <path style="color:black;" d="m1024 273h491q21 72 21 141 0 156-86 283t-223 187q-66 5-66 72 0 53 67 78 93 65 93 172 0 77-52 135t-126 67q-60 5-60 68 0 28 22 52 54 42 54 110 0 56-40 96t-95 40q-57 0-96-40t-40-96q0-67 54-110 22-22 22-52 0-63-59-68-75-9-126-67t-52-135q0-107 93-172 67-26 67-78 0-67-67-72-136-60-222-187t-86-283q0-74 21-141h491zm0-68h-540q-40 100-40 209 0 185 105 332t270 210q-71 33-115 99t-45 151q0 105 70 182t172 89q-81 61-81 161 0 84 59 144t145 60q84 0 144-60t60-144q0-100-81-161 102-12 172-89t70-182q0-84-45-150t-116-100q165-63 270-210t105-332q0-108-39-209h-540z" fill-rule="nonzero" transform="translate(0,2048) scale(1,-1)" display="block" fill="#000"/> <path style="color:black;" d="m1024 1775h491q21-72 21-141 0-156-86-283t-223-187q-66-5-66-72 0-53 67-78 93-65 93-172 0-77-52-135t-126-67q-60-5-60-68 0-28 22-52 54-42 54-110 0-56-40-96t-95-40q-57 0-96 40t-40 96q0 67 54 110 22 22 22 52 0 63-59 68-75 9-126 67t-52 135q0 107 93 172 67 26 67 78 0 67-67 72-136 60-222 187t-86 283q0 74 21 141h491z" fill-rule="nonzero" display="block" fill="#fff"/> </svg> ';
 if (what == 'k') div.innerHTML = '<svg xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" height="100%" width="100%" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" viewBox="0 0 2048 2048"> <g fill-rule="nonzero" fill="#fff"> <path style="color:black;" d="m1060 1261q172 3 282 17t198 33q55-54 122-103t94-84q48-65 48-162 0-89-43-149-77-113-235-113-92 0-192 40-91 36-132 78-85 82-124 216-15 46-16 117t-2 110z" display="block"/> <path style="color:black;" d="m988 1260q0-40-2-110t-15-118q-41-134-124-216-43-42-132-78-102-40-193-40-158 0-234 113-43 60-43 150 0 98 48 161 25 36 96 89t120 98q87-19 196-33t283-16z" display="block"/> <path style="color:black;" d="m1024 1009q7-28 12-39 10-39 23-66 6-17 17-39t25-52q8-17 17-41t18-49q8-20 8-43 0-49-35-83t-85-35q-119 0-119 119 0 23 8 43 22 65 34 90 13 29 24 51t19 40q13 33 22 66 2 6 12 38z" display="block"/> </g> <g fill-rule="nonzero"> <path style="color:black;" d="m1133 865q0 29 6 82t21 84q37 81 106 150 20 20 86 54 65 32 139 32 56 0 111-19t84-60q25-34 25-110 0-60-49-112-32-33-69-66t-71-79q-58 20-161 31t-228 13zm-73-78q172-3 282-17t198-33q55 54 122 103t94 84q48 65 48 162 0 89-43 149-77 113-235 113-92 0-192-40-91-36-132-78-85-82-124-216-15-46-16-117t-2-110zm458-173q-184 51-490 51-308 0-496-52l15-101q189 49 481 49 291 0 474-48l16 101zm26-299l-30 118q-195 45-490 45-292 0-488-45l-32-119q190 58 521 58 159 0 295-16t224-41zm-1081 207l-33 187q-1 0-3 4-5 7-28 18t-55 40q-44 37-68 60t-45 50q-61 84-68 203-10 115 93 229 104 114 281 107 66-4 155-32 29-12 59-23t61-24q16-8 28-16-5 21-5 42 0 78 55 133t134 56q78 0 133-55t55-133q0-16-4-42 14 9 27 15 46 20 121 47 86 29 155 33 177 8 280-107 101-114 94-229-8-119-69-203-20-27-45-50t-67-60q-33-28-56-39t-27-19q-1-2-2-3t-1-2l-32-188 66-247q-50-45-224-74t-402-29q-232 0-408 30t-221 77l66 244zm525 266q0 40-2 110t-15 118q-41 134-124 216-43 42-132 78-102 40-193 40-158 0-234-113-43-60-43-150 0-98 48-161 25-36 96-89t120-98q87 19 196 33t283 16zm36 251q7 28 12 39 10 39 23 66 6 17 17 39t25 52q8 17 17 41t18 49q8 20 8 43 0 49-35 83t-85 35q-119 0-119-119 0-23 8-43 22-65 34-90 13-29 24-51t19-40q13-33 22-66 2-6 12-38zm35 520h-71v131h-83q-34 0-34 33v1q0 33 34 33h83v85q0 35 36 35 35 0 35-35v-85h86q33 0 33-33v-1q0-33-33-33l-85-1-1-130zm-147-694q-125-2-228-13t-161-31q-30 37-69 74t-70 71q-50 51-50 112 0 75 25 110 27 40 83 59t113 20q72 0 138-32 65-34 86-54 68-69 106-150 13-30 19-83t8-83zm112 345q-2 10-5 17-6 20-10 29-3 7-8 17t-10 23q-3 7-7 18t-8 22q-3 9-3 19 0 53 51 53 53 0 53-52 0-13-4-19-14-38-15-41-15-30-19-39-7-16-9-29-3-6-4-10t-2-8z" transform="translate(0,2048) scale(1,-1)" display="block" fill="#000"/> <path style="color:black;" d="m1518 1434q-184-51-490-51-308 0-496 52l15 101q189-49 481-49 291 0 474 48l16-101z" display="block" fill="#fff"/> <path style="color:black;" d="m1544 1733-30-118q-195-45-490-45-292 0-488 45l-32 119q190-58 521-58 159 0 295 16t224 41z" display="block" fill="#fff"/> </g> </svg> ';
 if (what == 'q') div.innerHTML = '<svg xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" height="100%" width="100%" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" viewBox="0 0 2048 2048"> <path style="color:black;" d="m1588 793q-83 30-233 49t-333 20q-178 0-326-18t-233-49l50-91q84 25 218 36t293 11q159 0 294-11t219-37l51 90zm226 668q-57 0-97 39t-40 97q0 56 40 96t97 41q56 0 96-40t40-97q0-57-40-96t-96-40zm-1578 0q-57 0-96 39t-40 97q0 56 39 96t97 41q57 0 97-40t40-97q0-57-40-96t-97-40zm1208 110q-57 0-96 39t-40 97q0 56 39 96t97 40q57 0 97-40t40-96q0-57-40-96t-97-40zm-840 0q-57 0-97 39t-40 97q0 56 40 96t97 40q56 0 96-40t40-96q0-57-40-96t-96-40zm1036-1288q-49-43-220-72t-394-29q-227 0-399 30t-217 75l63 240-28 157-88 153-85 622 49 19 274-462 6 550 68 12 209-553 112 595h69l112-593 207 551 69-12 6-550 275 463 47-22-83-619-89-153-28-159 65-243zm-96 32l-30 118q-195 45-490 45-292 0-488-45l-32-119q190 58 521 58 159 0 295-16t224-41zm-26 299q-184 51-490 51-308 0-496-52l15-101q189 49 481 49 291 0 474-48l16 101zm-494 1007q-57 0-96 40t-40 97q0 56 39 96t97 40q56 0 96-40t41-96q0-57-40-97t-97-40z" fill-rule="nonzero" transform="translate(0,2048) scale(1,-1)" display="block" fill="#000"/> <g fill-rule="nonzero" fill="#fff"> <path style="color:black;" d="m1588 1255q-83-30-233-49t-333-20q-178 0-326 18t-233 49l50 91q84-25 218-36t293-11 294 11 219 37l51-90z" display="block"/> <path style="color:black;" d="m1544 1733-30-118q-195-45-490-45-292 0-488 45l-32 119q190-58 521-58 159 0 295 16t224 41z" display="block"/> <path style="color:black;" d="m1518 1434q-184-51-490-51-308 0-496 52l15 101q189-49 481-49 291 0 474 48l16-101z" display="block"/> </g> </svg> ';
 if (what == 'r') div.innerHTML = '<svg xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" height="100%" width="100%" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" viewBox="0 0 2048 2048"> <path style="color:black;" d="m1161 1706h170v137h274v-375l-222-171v-478l170-170v-205h153v-239h-1365v239h153v205l171 170v478l-222 171v375h273v-137h171v137h274v-137zm-597-1246v-102h920v102h-920zm460 1092h-512v-46l73-55h879l71 55v46h-511zm0-169h-350l60-47v-57h580v57l60 47h-350zm0-546h-290v-46l-60-58h700l-60 58v46h-290zm0-172h-414l-46-43v-58h920v58l-46 43h-414z" fill-rule="nonzero" transform="translate(0,2048) scale(1,-1)" display="block" fill="#000"/> <g fill-rule="nonzero" fill="#fff"> <path style="color:black;" d="m564 1588v102h920v-102h-920z" display="block"/> <path style="color:black;" d="m1024 496h-512v46l73 55h879l71-55v-46h-511z" display="block"/> <path style="color:black;" d="m1024 665h-350l60 47v57h580v-57l60-47h-350z" display="block"/> <path style="color:black;" d="m1024 1211h-290v46l-60 58h700l-60-58v-46h-290z" display="block"/> <path style="color:black;" d="m1024 1383h-414l-46 43v58h920v-58l-46-43h-414z" display="block"/> </g> </svg> ';
 if (what == 'b') div.innerHTML = '<svg xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" height="100%" width="100%" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" viewBox="0 0 2048 2048"> <path style="color:black;" d="m1290 582l-42 102q-102 22-224 22-121 0-222-22l-42-101q124 31 264 31 138 0 266-32zm-84 206l-29 70v27q-76 11-153 11-75 0-152-11l-1-27-27-70q85 15 180 15 96 0 182-15zm-35-378q-40 30-80 95h-32q0-49 23-95h89zm-206 0q23 49 23 95h-32q-39-64-81-95h90zm59-18q-14-57-34-79t-53-46q-36-25-85-42t-109-9l-281 39q-17 2-30 0t-26-2q-21 0-53-9t-51-28l-97 159q18 20 32 28t33 17q58 27 124 33 28 2 55 1t56 2q54-9 108-16t110-16q60 0 81 12 11 6 35 22t48 47q-53 6-108 20t-97 31l104 258q-78 45-109 72t-49 64q-26 46-33 89t-7 77q1 60 28 132t104 130q63 48 123 99t119 119q-74 38-74 121 0 56 39 96t97 40q56 0 96-40t40-96q0-82-74-121 58-68 117-119t125-99q75-57 102-129t29-133q0-34-7-77t-32-89q-20-36-50-63t-108-73l104-258q-44-16-99-30t-106-21q23-31 47-47t36-22q21-12 81-12 54 8 108 15t110 17q27-3 54-2t57-1q64-6 124-33 18-9 32-17t33-28l-98-159q-18 18-50 27t-53 10q-13 0-26 2t-31 0l-280-39q-60-9-111 8t-85 45q-33 27-52 46t-33 77zm-37 744v-85q0-37 37-37t37 37v86h90q35 0 35 36t-35 36h-90v90q0 37-37 37t-37-37v-90h-88q-35 0-35-36t35-36l88-1z" fill-rule="nonzero" transform="translate(0,2048) scale(1,-1)" display="block" fill="#000"/> <g fill-rule="nonzero" fill="#fff"> <path style="color:black;" d="m1290 1466-42-102q-102-22-224-22-121 0-222 22l-42 101q124-31 264-31 138 0 266 32z" display="block"/> <path style="color:black;" d="m1206 1260-29-70v-27q-76-11-153-11-75 0-152 11l-1 27-27 70q85-15 180-15 96 0 182 15z" display="block"/> <path style="color:black;" d="m1171 1638q-40-30-80-95h-32q0 49 23 95h89z" display="block"/> <path style="color:black;" d="m965 1638q23-49 23-95h-32q-39 64-81 95h90z" display="block"/> <path style="color:black;" d="m987 912v85q0 37 37 37t37-37v-86h90q35 0 35-36t-35-36h-90v-90q0-37-37-37t-37 37v90h-88q-35 0-35 36t35 36l88 1z" display="block"/> </g> </svg> ';
 if (what == 'n') div.innerHTML = '<svg xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" height="100%" width="100%" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" viewBox="0 0 2048 2048"> <path style="color:black;" d="m490 683q4 9 13 27 17 42 17 50-2 28-29 28-20 0-47-57-4-8-12-14-27-28 8-47 32-19 50 13zm590 371q70 92 69 205-4 33-38 33-46 0-36-34 3-55-11-91-23-57-51-85-15-30 18-44 32-15 49 16zm-261 488q-5 36 2 78-60-12-113-56-32-17-15-47 17-31 47-10 21 11 38 20t41 15zm937-1268q-1 0 2 54t3 118q1 125 0 258t-36 267q-34 128-75 218t-89 157q-72 108-196 183t-259 98q4-23 3-47t0-46q97-33 183-82t126-106q48-66 89-156t75-219q34-133 35-266t1-259q0-63-3-117t0-55h141zm-677 1423q157-10 291-81t228-182q66-78 124-188t94-233q40-143 50-300t11-292v-218t-155 0-404 0h-671q-9 0-9 49t7 79q4 24 19 68t50 107q16 32 76 93t138 143q45 46 70 116t22 127q-37-30-81-49-212-76-307-220-7-9-45-81-20-38-34-52-19-19-55-21-56-3-87 54-42-12-75-10-56 21-81 45-51 51-66 102t-16 110q0 84 104 222 122 159 130 242 0 36 7 81 6 31 25 60 13 20 17 27t17 23q9 12 15 18t15 18q11 13 28 30-53 146-43 301 199-71 334-223 33 113 130 183 80-56 127-148zm-675-836q29 20 6 48-24 23-53 4-61-40-65-105 1-33 39-31 36 3 35 37 8 32 38 47zm252 498q22 11 22 11 30 12 19 44-12 30-46 20-118-43-163-158-7-33 24-45 31-10 42 22 8 17 12 22 11-9 29-12 61-10 71 54 3 22-10 42z" fill-rule="nonzero" transform="translate(0,2048) scale(1,-1)" display="block" fill="#000"/> <g fill-rule="nonzero" fill="#fff"> <path style="color:black;" d="m490 1365q4-9 13-27 17-42 17-50-2-28-29-28-20 0-47 57-4 8-12 14-27 28 8 47 32 19 50-13z" display="block"/> <path style="color:black;" d="m1080 994q70-92 69-205-4-33-38-33-46 0-36 34 3 55-11 91-23 57-51 85-15 30 18 44 32 15 49-16z" display="block"/> <path style="color:black;" d="m819 506q-5-36 2-78-60 12-113 56-32 17-15 47 17 31 47 10 21-11 38-20t41-15z" display="block"/> <path style="color:black;" d="m1756 1774q-1 0 2-54t3-118q1-125 0-258t-36-267q-34-128-75-218t-89-157q-72-108-196-183t-259-98q4 23 3 47t0 46q97 33 183 82t126 106q48 66 89 156t75 219q34 133 35 266t1 259q0 63-3 117t0 55h141z" display="block"/> <path style="color:black;" d="m404 1187q29-20 6-48-24-23-53-4-61 40-65 105 1 33 39 31 36-3 35-37 8-32 38-47z" display="block"/> <path style="color:black;" d="m656 689q22-11 22-11 30-12 19-44-12-30-46-20-118 43-163 158-7 33 24 45 31 10 42-22 8-17 12-22 11 9 29 12 61 10 71-54 3-22-10-42z" display="block"/> </g> </svg> ';
 if (what == 'p') div.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 2048 2048"> <path style="color:black;" d="m1024 205h-540q-40 100-40 209 0 185 105 332t270 210q-71 33-115 99t-45 151q0 105 70 182t172 89q-81 61-81 161 0 84 59 144t145 60q84 0 144-60t60-144q0-100-81-161 102-12 172-89t70-182q0-84-45-150t-116-100q165-63 270-210t105-332q0-108-39-209h-540z" fill-rule="nonzero" transform="translate(0,2048) scale(1,-1)" display="block" fill="#000"/> </svg> '; 
 if (what == 'T') return WhiteToken();
 if (what == 't') return BlackToken();
 div.firstChild.setAttribute('class',what);
 return div.firstChild;
}

function WhiteToken()
{
	var a = svgelement('svg'); a.setAttribute('width','100%'); a.setAttribute('height','100%');
	a.setAttribute('viewBox', '-50 -50 101 101');
	var c = svgelement('circle');
	c.setAttribute('r',40);
	c.setAttribute('stroke','#000');
	c.setAttribute('stroke-width','3');
	c.setAttribute('fill','#fff');
	a.appendChild(c);
	a.setAttribute('class','T');
	return a;
}
function BlackToken()
{
	var a = svgelement('svg'); a.setAttribute('width','100%'); a.setAttribute('height','100%');
	a.setAttribute('viewBox', '-50 -50 101 101');
	var c = svgelement('circle');
	c.setAttribute('r',40);
	c.setAttribute('stroke','#fff');
	c.setAttribute('stroke-width','0');
	c.setAttribute('fill','#000');
	a.appendChild(c);
	a.setAttribute('class','t');
	return a;
}

function svgmerida_notation(what)
{
 var a = svgmerida(what); a.style.width = '1em'; a.style.verticalAlign = '-1px';
 var div = newel('div'); div.appendChild(a); var ret = div.innerHTML;
 return ret.replace(/class="\w"/,'');
}

/*
function StaleWhiteKing()
{
	var div = newel('div'); div.appendChild(svgmerida('K'));
	div.innerHTML = div.innerHTML.replace(/#fff/g,'#ffa');
	return div.firstChild;
}
function StaleBlackKing()
{
	var div = newel('div'); div.appendChild(svgmerida('k'));
	div.innerHTML = div.innerHTML.replace(/#fff/g,'yellow');
	return div.firstChild;
}

function CheckedWhiteKing()
{
	var div = newel('div'); div.appendChild(svgmerida('K'));
	div.innerHTML = div.innerHTML.replace(/#fff/g,'rgb(255,230,230)');
	div.innerHTML = div.innerHTML.replace(/#000/g,'rgb(150,0,0)');
	return div.firstChild;
}
function CheckedBlackKing()
{
	var div = newel('div'); div.appendChild(svgmerida('k'));
	div.innerHTML = div.innerHTML.replace(/#fff/g,'red');
	return div.firstChild;
}
*/

function svgelement(tag)
{
 var a = document.createElementNS("http://www.w3.org/2000/svg",tag);
 if (tag == 'svg') a.setAttributeNS("http://www.w3.org/2000/xmlns/", 'xmlns', "http://www.w3.org/2000/svg");
 return a;
}

function blankimage()
{
 var a = svgelement('svg'); a.setAttribute('width','100%'); a.setAttribute('height','100%'); return a;
}

function solidcolorimage(color)
{
	var a = svgelement('svg'); a.setAttribute('width','100%'); a.setAttribute('height','100%');
	a.style.backgroundColor = color;
	return a;
}

function settingsicon(size,black,white)
{
 size = size||16; white = white||'white'; black = black||'black';
 var svg = svgelement('svg');
 svg.setAttribute('width', size); svg.setAttribute('height', size);
 svg.setAttribute('viewBox', '-50 -50 101 101');
 var c = svgelement('circle'); svg.appendChild(c); 
 c.setAttribute('r',50); c.setAttribute('fill',black);
 var c = svgelement('circle'); svg.appendChild(c);
 c.setAttribute('r',25); c.setAttribute('fill',white);
 for (var n=0; n<8; n++)
 {
  var x = n*Math.PI/4;
  var c = svgelement('circle'); svg.appendChild(c);
  c.setAttribute('cx',(50*Math.cos(x)).toFixed(5)); c.setAttribute('cy',(50*Math.sin(x)).toFixed(5));
  c.setAttribute('r',10); c.setAttribute('fill',white);
 }
 return svg;
}

function tomovetriangle(flip,whitetomove,empty)
{
 var svg = svgelement('svg');	
 svg.setAttribute('width', 17); svg.setAttribute('height', 18);
 svg.setAttribute('viewBox', '0 0 17 18');
 var points, p = svgelement('polygon'); p.setAttribute('stroke-width','0.7'); p.setAttribute('stroke','#000');
 if (!empty) svg.appendChild(p);
 var toppoints = '0,0 16,0 8,17';
 var botpoints = '0,17 16,17 8,0';
 if (whitetomove) points = (flip) ? toppoints : botpoints; else points = (flip) ? botpoints : toppoints;
 var fill = (whitetomove) ? '#ffffff' : '#000';
 p.setAttribute('fill',fill); p.setAttribute('points',points);
 svg.style.display = 'block';
 return svg;
}

function nexttriangle()
{
	var a = tomovetriangle(true,false,false);
	a.style.transform = 'rotate(90deg)';
	a.style.height = '1em';
	a.style.display = 'inline';
	return a;
}

function resigntriangle()
{
	var svg = svgelement('svg');	
	svg.setAttribute('width', 100); svg.setAttribute('height', 100);
	svg.setAttribute('viewBox', '0 0 100 100');
	var points = '98,2 2,49 98,98';
	var fill = 'black';
	var p = svgelement('polygon'); p.setAttribute('stroke-width','5'); p.setAttribute('stroke','#333');
	p.setAttribute('fill',fill); p.setAttribute('points',points);
	svg.appendChild(p);
	var p = svgelement('polygon'); p.setAttribute('stroke-width','14'); p.setAttribute('stroke','black');
	p.setAttribute('fill','black'); p.setAttribute('points','2,98 2,2');
	svg.appendChild(p);
	return svg;
}
function backtriangle()
{
	var svg = svgelement('svg');	
	svg.setAttribute('width', 100); svg.setAttribute('height', 100);
	svg.setAttribute('viewBox', '0 0 100 100');
	var points = '98,2 2,49 98,98';
	var fill = 'black';
	var p = svgelement('polygon'); p.setAttribute('stroke-width','5'); p.setAttribute('stroke','#333');
	p.setAttribute('fill',fill); p.setAttribute('points',points);
	svg.appendChild(p);
	return svg;
}
function navitriangle(which)
{
	var svg = svgelement('svg');	
	svg.setAttribute('width', 100); svg.setAttribute('height', 100);
	svg.setAttribute('viewBox', '0 0 100 100');
	var leftpoints = '98,2 2,49 98,98';
	var rightpoints = '2,2 98,49 2,98';
	var points = (which == 'left') ? leftpoints : rightpoints;
	var fill = '#fff';
	var p = svgelement('polygon'); p.setAttribute('stroke-width','5'); p.setAttribute('stroke','#333');
	p.setAttribute('fill',fill); p.setAttribute('points',points);
	svg.appendChild(p);
	return svg;
}


function finishimgdiv()
{
 var url = "url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAAA8CAAAAAC1VEASAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAAUdEVYdFNvdXJjZQBGaW5lUGl4IEFYMjAw8FsFYwAAAC10RVh0U29mdHdhcmUARGlnaXRhbCBDYW1lcmEgRmluZVBpeCBBWDIwMCBWZXIxLjAznjXKWAAAAAd0SU1FB+AJBwkkImEOcCcAAAANdEVYdEF1dGhvcgBQaWNhc2HDvLtrAAAADnRFWHRDb3B5cmlnaHQAICAgIEgKtkUAAAAgdEVYdENyZWF0aW9uVGltZQAyMDE2OjAxOjAxIDAwOjAyOjMxKexiRgAAACF0RVh0Q3JlYXRpb24gVGltZQAyMDE2OjAxOjAxIDAwOjAyOjMxoWMBgAAADTpJREFUWMN1WAdcFMcan6vcHR1BECTSRAEhKlbsPhHUWANK7GIEO7YYEQMqUZSIJNGnImoiWLCgwfLsXYkaG4oaQUDg6B0Oru3uvNnd2eMOz/n94Oab9v/6fLOAoiCFGgkpEv1AmmAGcPtSH4+wQyQ9ReovggY7QPtWyLYOFIT6vfYJlmIZ5AAw4mfcsCCqwvy6ik95eQUFn8rqmhob6yvK5CVFBUWlZeVyeWmpXF5ckJ9fWCyvqKwoKy4sKCwqpP8Vl1XW1DcqVBotodWo1WqlUqnWqFVqLcuPlqA5Y0Fo6Pwpg9dN87e3c3b3C4rasjn2h5kTg4f7eXgPCQoKHDN6VPDYQZ6OTh7+Y6aFho4b5N29e08fL8+ePv2DZixat3VP+vkr1y//lXnmZFpaxrnzp06c+l92Tm7Oq+ysG7kfm0kMguD+HQHYZuvr62ItwITEz8/by80U9Xj0n4uvl6enBHDNytvTxcmCp6P5QgEmBHwzO0sZOqVrn5QmyIIgmPeD8co1JcXX1tuxfd6A0tL3uReGcPgnit/+87c3d6Zk0fsnVzIWW2HSZnjYrBAnpmseOHVAVzzs90YHQty2Zk4FlhlImVfd2AXimSSi3o0CLIP+LxCl6cWBdDlKK/5KD8z95AoImwMYiXvcpCp/wKvcX+pAmhPxWL9z7/IqdnRiCZPZHyvkpacd8NzAc2/efnjhwoG4X6NBLrtjMhSBlNgw3b65sD4SD3u+op2bBoGl3+Ex13FBkxYPEGElu82IDJ83EpsEWPqPHvvNRDMOpHPM5Su3/t5mg8ngt5WfskyZbu/b70/6sQoHbi91IB/6A7Nu3v4czzyRUCzj6yzaybd334E92y2Om9DOwbWPGx+r66vwiNkjWY+xHDnhayEedtOpi3wkc47PuHx/LmZatCw+ITHGn89SsvCbt+8//d0Vn+2zav265d/PG8RhWdsK9ZGlJgac2P3DgbQeAEMbkYK3cjZ9hwhiGd7c5Qit/IueWIdz2ujJhl1YidIVhxPjYhawWhMOTkyahQ06ZVPE/Pk9bR9zIDVLQUAN2roDg3j9iwjFtzhc3M/R6ePEVxxIPa3guhi8tutlmoVsD4aQboQwgx23OArbGhtX+Tyn45AOxtJJ/G9Kcv7cMBBv7J5x78HLR/0x1eMSnR1OYc/nz5ar0KkVk7Ek3rdokGuuDCmL1Sr2Y73tpsd3ej2lOaRB8v15rlOGddEp0tS3r39AAOdHFqO/D5+/dIwMh4vL5JnLN+7YYI9nPQ5+KK2o/oMNQ1ks2bgBT2yhQY663GLSCp25+mJfkAwe07Obzkn5DuALTWCmWyTxGhEYPL63CcOCwHv6NHfsPb0WRi5dHSg5psK5K88H7+h2KPfR3cXm2JH7XDl76Lfobtxxfsu2Jv66xIIjHUIGOlniiOJ9gRnkn9sbMchbjuXe91CS/h0HvPh7AlLqp7pkNettc5sqlQs+MLLw1cO7aUM4hG9PZaUtEDPdPgdPpARy6JvqIWv452K8cVA2MnGcObtNtg1JCu/IuFOXViMbxplz5Cj60mhdypGJrVB9nF07rIJSbuTGo+sg68K5XM4c8AhdwxE41qVxKpKgLulkX1NLkWSUDrNfMX0jxnHq2qugVGnspPc9SG3jlBhVjUEqFmLhpP0mhy73Zmd5Av+dv+1JWaED6R8RGRnhpcs27udqahs1iZw5NtRolTh3OaQQZAIHMqcMg6guuHTx7v61l9Cks7WZlUwsFotE6E9qa9fZ3kbAFwiEQvQnsTC3sJCI0JRIwOPxRF08PDz9HDnMnitjYsJZPxAFxO2Zxo3PKMUgZOun4tISeabH0Ft5Obeyrj+6eSwt63Rq8g50EW9fPmPOqi3rZ89ZH7tr/+HDB9P2HTm7b/4AP5/uzl3sHNxcLGVSqcxUKpFZW1tbiplmYmpjawp4QpFAwDf/qZbE9wlbntx0mVIMSY1ao9WgikDV1qpoaWlRNNY3NLU019c1tyjaUFO2KlWtDdVVqKKQy+XlZbl3MjOu/3N2X+LmqCXL18ZFx8bF/5ywKWKQ2HZGVEjwyH0VJE71uF3sHCqn0xlJGqmwdGUUNKy5CK0KVStqZVsr4qilVdFKN4XitKP/HUVTY4OSKyS4TRc6Ty9HJV7V61INRX0JRa1obmpSkbBjuWfYg2dtBj2BenWXbvJK16kl6JQosxklxiSBRFPZx5wbv27dvDXzaWGN1ri06vLiZrT2tNXQZ0ZBsmyRJCSV7mxzk+iwFSmQqHq4Y6K7hAf4Ej6Q9lp8U04Y40Q+t28K+kk3CX5vDITKkM6j7wrNItHcss/UVHV9EUo3nRzd+wXND+rl7mQqC7mnNCLIxc7CuWh9KggpxiWsoSR/gsUKuvPQR3JGaVhWt2YvtwZm3hOSjj9oIKGq4H7mD8NtemSpO8pLvAvg97+PuslgbhXUGUoP5ABYraXHiZWSEUX6JTSsPtgLWAza+ESD0hWJq/HKRM++fzUZQJBU02Zgm4Kch4gDkU1GbXIIrGRZe+knSNcThWxJsuD32lwAYVX2MyXDniKvCGqv9HU/biiL5ryjMKSc7sSCJS26B4I+SDovooXtrpaNLGx/bsAbPqKJdzSQqovz7pfRSlcep6eG/0uRt/r0f8Y9UOimfe7PG/yUceEEsKLNqCRpvEgF233ei5/QprN5/Wowgi6fYDa69caiSpGqnssz34ZGTjlH1bfHCFkxm+eWxRLbwEolZQwkFazRsLolky2crxKcLM8DLPcyW/OGANPtjTUfqZbdwOEosk7VcrdM3duMVCVLZFtYv1bFgHWEUZA94CfuedawQDTsJcFqAV62GZYDWf8L3VarvTP/iaZofVIdPfC49+wqbr/2mYdoqpzlrG4Z2EQZAYEwGcRyaiTfjhBMLyM5116IdddWrIGaBzZL2kgVwThoW7TLJUYSJL4yUuT8iN1CNq4A0aRRkD1gE8QmJMlr3SWxapJ5qx4Fy7GE9A/x3i5QQeGFMN0qTolNf8lRtERFsm9GVTSyiVHDH6bjBFuRVP9X6nyJPeiGQ7ieETWPbINadXvO2c+rYlX5bAjf+7mWC/wYEKWGeq9f3YbjwohmVnQapWq6eFQJnfThh+DgYjYAmf1HrCe2Uhx9ynpaIaOvvIl8lzMaTkVNq8Am0qjhz5jNqW1P2dq/3U1jGA9RrHW7hoafHvlIU8ot5jNpE2mubnxJwd+FCyppsKaFIps/VJxFYelcEA+hMZucMQ8rb58glXtNPO4yKj5ilYAEyvh6Wh6a1lzqOquVgvXJPWQrtIooEE+7PUyyAVsa2/ktDAW/QCOSkPCUNKxMb4YsnSCcRDsozB0dgJguGCmY/AK5deOFB1pYE2sHbOKou778A7Tq7/uCEfmk7vKC78fz90Kj6koXfFdhkLYvSK2TmM4B+x8bIPFsjaNXQjFBadVVqeNswMALlW+mCL1uoAVFE4SdL+ulMfhkgHU6bM+cBglyZqX+dUdWreAHfGKy8BKnYygSak/62vqFrVw4qY+dQDTxhfpdmMQqEaW7lhVS0e4mvU8e8H9OLmx+Ien8oVdIwCMgxPDeJZ65W8UT9NbX/+n3gCCh8nVEJ5m5TCwQ9/ilRHk3SCKKpKuC/Y68pfL2LytI85mWXlcZL2UwSAN1fVtseHE37RL53qLXalOcBp9v0CBhniTMCAlZfTK/vjyxm0i2kq54ngXwh78i9bIxBc9b977PFj50I/RATog7SEIR+VPEwXnMSz/eyXby9boWRUt9eWV5ZWVRWrCpsEt0JZqqXSi0z9IYVCwwnd//ISTZi4wk9ECo4+Lp8g4lCJHtJ1rcwhSZZwMd7IPWbtp+IPVg8o+jnUyFpmOvKVCwNkebi3+u16/UkLsf5QU8hYwQzCcrg1Q/q6pjKadMN3NKYYJM3XA5zNXW0kImkchMTCztfH6upJOIar+jeObHjnXHITDqFSR138XaQchkENnSoTKAVMVaE//rTEoiCdWT3YvnufJFw4dOTb5SrmIMe6c/b9wrw69qqCWBCXkUYQREsQH8SH5WlVK54/hjcphsjMxIaNtWSe0/KNUagnZOCn4KA19d/OxTHbkTTCnS+8TXnlYq5vN3GfvCeNcXhLerUb3RbGAtSeKAqFkltEho/Kz6In4B498RxkBej+503FjpqTlq22mXTo+q2aaLWriQUOy2ki4u7agr1HaD4LdaYyC3XH1uQmMldM0GiVcWwaXNMbKtXI1BXO0Fxr3Rr1c4dSWBwNdGQf4yH5EDjT0WqNzxYFwuO0W09pYm4ysMFoSC7hfxNWMIkgxGPe8IwuSbY2BSkfFKHV7yAMuqWZBaN0kKCwLrokWWO1WfL0cWSwZBbzRGJKFSQVj1F54lbUk2toeY48j8rrJMDfO1WZFia7KgyPj7IR6MzVEbAVHvBIuUX3r7yBfw/a+isyH52EF2m8khmqveJqFvvvBI2c33umgEBDZvAstIIxvYq2iPFPykoJPRbXuzx0xsKnYA21T4+Wo6flAatNqnagf5P5AQbKZTBwzIAAAAAElFTkSuQmCC')";
 var div = newel('div'); div.style.width = '100px'; div.style.height = '60px'; div.style.display = 'inline-block';
 div.style.background = url; div.style.backgroundSize = 'contain'; div.style.backgroundRepeat = 'no-repeat';
 div.style.width = '75px'; div.style.height = '45px';
 return div;
}

function correctimgdiv()
{
 var url = "url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC0AAAAtCAIAAAC1eHXNAAAABGdBTUEAALGPC/xhBQAAAAlwSFlzAAAOxAAADsQBlSsOGwAAAAd0SU1FB9YGCxUVOrumeQ4AAAqXSURBVFjD7VhrdFTVFb6veWaSTGbIJAFMyFNqCCx+VFRUouENKiu6ahdCu9plta34Q2uDLSqxvlpri1VWV13WHwqtra5WUanyUMQK9YXaLmOTQAzJGJLM+z33ffude24mD0Ip0R/+6M1dN3dm7j3nO3t/+9t7H9YwDOYrcHDMV+MQZvZaJBvtiXR/luiP5WOSJrlsLr9rVpOvoWlWU4mjZAYDsufkl2OnPtz9rz+8FTycVjLeUm+9t77cWV7Eu7NaLiSG++InMumM1+FdUbviW4s2N/jqv3wcz3U998jRX0fV2Mr5K9pq2873N/lsPs3QdEM3GINlWI7leJaPytGeWO/B/oMHug/WFdXes+yuy2su/3JwdIU//fYL38my2Vsu+cHKeSt5g4cjgADTM5NfZVmWeJoV7LxdZdXX+vftPLKzoahhd/suv9v3hXDcd/j+Rz94bMuyH26+YLOoiLIm43mO48w3yaywBLUHrgQXi3/kACagcdgcT3c988TbTzzatuOGhRtniGPDs+0D8uAjax4OOAI5NYdZYHy6aJZMZOGgD1PjmBCsG7gMzzgF56gYuv3V25dXLt+x+lfnjKPtmRXuYvddV2zTVR1e4BiCAFfTAqyJglwKvmHNoahVCjeAQrSB5cCd7a/fO8c+56mrnzwHHBv+1K461G3Lfiorss7oICBHbWEQPhagTHmLDmXhMK0iqqJqqDbOhjfgo843OheXLv75iof+Jx27//D9A9LA7ZfdlpEysi5jTRhLYzRNJ9FBYgSHeUPjhZ703oogFv+0iBixc3aP4BE1UdGVtJTuWNbxcnDvnu6Xzm4PREfrrisfv+6xUqGEUBImNQOSMoMzcVPvTCTHOEUM4i1MnJATi8sW1xXXvhd9PyyGMQJxLssl1PiP/trRv+WEQ3D8NxxLnrx4SfOSNbWrRFXCywhCCwrDs6xFC/o3BUQhdDNKJqvm1s5e7Rbc+Gbf8H4YAw/DqDAVguiVvr2ZUHrXtc+c0S/Pdz2fYlLLa9sgl9TsxCNjBrducKsTs9NvJp5YUkJKwBjt522gIHAM54cBgr6L0YBydcOqo6F3uiPdZ8Sx453frFu0LitnVU2d6G8yDUMwqQgeRlcMhQKaeAJEUknmtfyGudcQLo8deIVwy1wSeIWPOSm3fvG6B956cHocH576KCSHmgPNIDlmwtDUklOsQkEQTOZZsFNGzUSkSPt57VPiCE4pmJO+ktfEhRUL3w29nxJT0+D44yfPtlQvBGRFV60YGXuT3OjWxPQnoCk8gKukS0O5oWur2wUQafIh6jK1rulKjKABGTzbOLvxhe4Xp8FxJHikMdCQV/KYQyGzWo6wTsxnWgij4Bz/3vwpmAteGlhaais9nbkBRzmcRbxDFkPWg9fzar6psunVE69OxRHNReNS3OvxIoNgPrJc4nV9Ig0neqHgL9BlJD9a4axs8bZMK5R1ntqkkhojmTUgoJR5vP2pk1ProJ5Ir91tR3BKhkR0iGVVRjGFkddZneoHlfaJSRYfJVUGPW9qvPGMiYNhkZsQPlgeVLFgURtvE1kR6/e7/eM4BhInS92lkiYid0KxGI2BFBJpYhnenBjrhio4OQcEoIAEOAYyA5vrNk2LYCAzuLt/NyqTWQ4/JtbGHE1xYIkOp3ModWoSjriYcAku0IIkDoZA4QyOlDhEOW0pLTOcO7XA29yfPTnbOZszIwKPRMRotae61jPvdBD7hw8cGjk81z3HyTsL0VcgO0kXrO62ueJifJJfADAn51RGxcJJ4OmmZnIIfC0oBgVOuLnxe/M8814M7ulKflrlqiIU0ZWR/Mit599yOohdn+3+d7KnxlONQRBKVGwwOBDgI2W3IRh5GfxVJuFw292JZKIn1FPvr+cJH4gMR8VoXI5fPeeq9XPX0ceumrvu76G3vXYvvIYYuaziMuTSKSB+2/u7YDZYU1RDFdZitKlmNNCgxgqr9I1+lognXTb3JBw+lw9mGI2G4KBKbyWq34Qcbypp2t5yd0GhcfCscHH5RR/H/lnhqoCEXzVn3RQQj/fsHDRBULkbT8WMec/oOS0HlKOJEBQedve7fJNw1HlroTYCSKkzYSn8SV/XA233XT13/ek2v6762kMjb6aV9Przpv765ImnelPHG4sbSYXAkAqIzg0ciKmEnIyI4ZSaVlWV1cEw1sbaqjyVk/SjwdeI1ArBIIlbZwOzyrcd2nbHOx2KoZ4O5cqKK0bF0VVVKyZ++crQ3vci79V56lBFY9FpNQ2ZH8oP9aX7ulPdg9nBqBzDaJiFFLYEnVbmKitxlk7CUezwVHmqNKKiBoTdXmSvr254P/bB1/+yZE9watmyofqa1srWid+8HT7yi65fwoMnMycHc4Of5z8fzY+ih8iqWRACwkNCzCzTQDuIAsykKMqC8uZpdL21plWURMItkgaIigeKA1D6zmM/6zh258RZQePvN95U+BgWIx0f3tlcegHmoyV7oYylpZGpoVbSBm+BBR+Qddc0rpkGxzcXXI/fTDqTRxVGUTQFU7YEFvwj9G77m9edSTG/e/TG+uI6yA+mIbWRQWhBIoUZr1pUfSz1k0SJgFF5RljbuHYaHHVltfP982VJwqNwDZQDnJeJ9qkN3jp0jisPrFF0eQqIHx/bimrUb/cTSTCs+mgcAUMrBI2maAICxlC1fD6/un4lP6FMmVQHdVxyRywTIxEH+8m6lQs0BdSb466y8cKVB1bJY8qD47VT+8AM6Ol4CmSsWKW1NE2zsiFTOcefeWrJfGrrpVvPWI8trV66KLAIYHVZM0BuRTVNIgOSqEsBV6DEVrz6oGVMTLP1o580ey8o1CVWZtatYgUvKuSqQBFI+pRJBEDiU5n0ppaNBeWYvk6O5WMX/v6iirIKm8PGOQXOwdoEGwId6dHO4bQPZ0cchv3llXtAi5AURgahrXaheSnUkaZNAUIBCA0ZRtLRF8s5SRTFj24+dvY+6s9dz9196B5A4YHDzvF2nrfxQADO4go0weRQhaN8MBf8Wvl8goG1WgbKTVxonaGYeYQ4BbWbhKJN0yR1JD7y4vUvLAg0n30f5vrmb3w88vFLvS/7GL9hNSWGYTNgFZ2YVq0oCaDnrvHWSKpEkqJhNXNmiI63WGYxpcImhBPEGGo4Ee5ctv10EGfcD3qo7cGMnH395Ot+w2+tFAPbNIEXeAbUE9DyEwqbNTHUgpiB2qNQqjFmVaoYlJiqpEYSkS0X3rJp4Q3n3O9vf7MTxXOgtJy32+AgTkCaY6DLHM/Rjpe2d3TTgdZKhcYTCBiSYTXCEUkOJ8OdrfdsWrhphvsfe4/vvW3fHcVOj7uoiBc4BoYQzIKNJztA5KbQJOiWQTC92eWgz9FgjFQ2xTH8rg1PT+uOc9gPgnhs+dutb/Qf8hX7nA4nh0YTLabZ8BrWHpA1AgGhk9xBnKhouXwulU/d0LLx3tbOL21/rDfa+/CRR45+fpTnBZfDiWCGm2iBiHyDvEEsopsqrCg5MQcXQjG3Lt161h2pmewXpqU04mh/34HjseNJMQkCEKqgUjDpidqszFmGLIoEtrZhLc+dw+YsO+P95Fg+Ppw+hfoNLY9LcJa5fLM9VSXOkpmNxv5/X3vS8R+B15cVBwJ55AAAAABJRU5ErkJggg==')";
 var div = newel('div'); div.style.width = '45px'; div.style.height = '45px'; div.style.display = 'inline-block';
 div.style.background = url; return div;
}

function wrongimgdiv()
{
 var url = "url('data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAIBAQIBAQICAgICAgICAwUDAwMDAwYEBAMFBwYHBwcGBwcICQsJCAgKCAcHCg0KCgsMDAwMBwkODw0MDgsMDAz/2wBDAQICAgMDAwYDAwYMCAcIDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAz/wAARCAAtAC0DASIAAhEBAxEB/8QAHAAAAgICAwAAAAAAAAAAAAAACAkFBwIKAwQG/8QAMxAAAQQBBAEDAgQDCQAAAAAAAgEDBAUGBwgREhMACRQhIiMxM0EkMmIKFUJDUWFxgbL/xAAXAQEBAQEAAAAAAAAAAAAAAAAHBggF/8QALxEAAQIEBQIFAgcAAAAAAAAAAQIRAwQFIQAGEjFBB2ETMlFxoSJCFCMkUpHB8f/aAAwDAQACEQMRAD8AeZrvrxiW2XSe5zjOLqLj+MY+z55kx/lUHlUEQARRSNwyURAARSMiQRRVVE9KE3F+7vrpvYs5kPTuRM0S01IzaZeZQFyWya5+hPSPvGKSogl4433gqkJPEnHqA96XdtK3c71ZuBQJxu6f6LyvhBGbdVWbK/6L8qSY8IilGQljAhIqgYvqK8Gqerm9t/YEG7OGzk118qo01q3yiJGZcVmXduAidmQMF7NMiq8G6Ko4RIQgoqimkTUalMzk0ZGSLAWJHPrfgD5xprJuSqJl2hJzRmhIWpYBQghwAbpASfMtW4eyRuzE4C/JNt1bns0p2YZBluX2Tq9nJtvcPy3nC/dVJ43F5/79TWn+N5xoDKZnaU6rZ9hEmKaOtxmbd2RXPEn5I7FcUmHB/pNok9E1v59vOZtLyt68hvSrTTq0e/g7CQvkepnCX6Q5JfuiqvDLy/z/AEbP8XqT92bPfZpq8o0uk3mqRX1VdZBFX+7K2BJWHIoALhQfdXhUOVx9fE4JNAhdTbMkVU40GjzpmTDRZY3LkfPfj/cJFS6h5URRoc9HCVwYlggISS9tQ0FgNL/VcAWYl0v0/bq97OwzfUer0p3AQazHMytDbiUGUwQ8NTkb6/aLDwKqpGlOKidOqq06SkI+MkADY8hL6Qjva2i2ul2b3WledhAsrEYiTqizij42LmI4TgtPCKqqsmSsuCTRKqtuNLwpijbrjIPY/wB7Fpu22pP0eXWDllqFpdMGhuZT7inItY6h3hT3OeV7Os8gREqkTsd4l47InqmodWjLiGSm/ONidy24PfvyMCPVLINPlpOHmXL95aI2pI2Tq2Ul7hJNik3Sq3YJD0YzeRqZm2WXVg4TtleWztzKM1+43Jbrj5Kv+/Yi9HjsG3tXWzO7edIjscDnOfIvKkiRFHgUFZcdV4QHxER+1VQHRFALqqA42Bmt+FSNlu+TPsPsmHosOgyCbWEjg8EsI3lfhyOqf4SjOx3E/pIk9NV9lvahiOsfytT76dHvpuI2gxqik4RY9fJFpp4LB5F/Wc/ETwf5bagridnUBWJWkwZgzvhwyywS59t/f25w9Z+qdIRlMTU7D8SXWhASkckj6WP2t+7jhywLL4kpm6rY8gW3PDIAHwF9gmnB/Ih7NmiEBJ9F4JEIVT6oip6rvdnuYr9qGjsjKp1XYXBlIbgw4sYCQHZLiF40ee6qMdpVHhXD/dRAUNw22z490u7TFdpWIQ7TIvnTpNnISPBq65GznTERR8rgC4YCgNCSEZEQonIiiqbjYF6jDswxPcbpRHtauRW5RiOTxDD8RlHI8touW3WXWnE5RUVDbcacFCEhMDFCRURLiRArVBhqAW3u3oWxieWk1wRCn5uApUuVNykKbzJCm3azju1wWRdub1hyTXHVKxzPJLBJl9MMSQgFQZiNh+kwyHK9GQ5XgeVVVUiJTMzMqYw7fZlWx3VHKJmHTJNeOaR4vyhYNR7pGdlK3zx/okgk9Ep7nmjeN7YNzl7i+N3DM2nahsWfxzf8rtEryGSw3nFVeSABB0VNe/hfZU+yr5HIf2lfbqrvcnnamZNfeeDjmNv19RUy1Z7BKf6yHpIp9U+4W3YhKn7I8Kfn6MUy82qfMKGfzHN3/m+Nt1Or0NGTUzsZH6RSYelJS1nGkae3ZxZwSL4Kz+0Ae1TbbkqdnWbTeqctMyx2CkPIaWGz2lX0BtSJt9gR/UlR+xp4+O7zJKAr2baA1T7JvcLz7Y5nEW8xGyF6IQeM4zvLsWYzyq+JwOR8jaKqqg8iYKq9TbVS52kCX0Eu/H2ItH96t3ZZRXfM02z6yM35VvStA5Es3yXlXZkI+G3T5UlVxtWnSUuScLhE9VlYoMSJF/FyZZfI2c+oPr8HAP086rSknTzl/McPxZU2BbVpB+1Q5SNw108DZlVZ97jY7icykZNmGUzJl5KAW1+ZFVkYzQ89GWgbHxttjyvAiv1UiIlIyIinNHfdzvNo1ZkUfAsmHw5I3/ExSrlmNsSOqCMxgXOrQSUERHsXcDERRxtzo30G7fFtQXZXq/OxN6+byYob5MfLZgFX9+F45UCde/8AXorvbD9j2t314wmUXmokyhpYboJJr62lAp0gCRfoEp5022y+n8yxy/49SMvDnlzRhwifEu5e/e74e6tWsnwKImLNpBlCBpT4ZIs2kBOm3Z2wNGnuNale4hrnCwXC62RZ3WRvnNeSTIcd5EnOz1jYyVQiRlDPubhck4ZoKeR0x9bGWx/aDj+xjbPjmm+POLNbqGyesLI2EaeuJzpd5EpxEVeFM1XgVIugIAIqoCes9o+yTTTY5gLmP6b43HpmZig5YznDKRYW7oIvDkmQaqbhJ2NURV6B3JAERXj1bI/T1dUWiiSBiRDqWdz6dh/ZxmHqZ1MiZliIlZVHhysPyp5J21KawYWAG17nj//Z')";
 var div = newel('div'); div.style.width = '45px'; div.style.height = '45px'; div.style.display = 'inline-block';
 div.style.background = url; return div;
}

function fullscreenicon(size)
{
	var black = '#666';
	var table = newel('table');
	for (var y=1; y<=5; y++)
	{
		var tr = newel('tr'); table.appendChild(tr);
		tr.style.height = (y==3) ? (2*size/6 + 'px') : (size/6 + 'px');
		for (var x=1; x<=5; x++)
		{
			var td = newel('td'); tr.appendChild(td);
			if ((y==1||y==5) && x!=3) td.style.background = black;
			if ((y==2||y==4) && (x==1||x==5)) td.style.background = black;
			if (x!=3 && y!=3)
			{
				td.style.width = size/6 + 'px';
				td.style.height = size/6 + 'px';
			}
			else
			{
				td.style.width = 2*size/6 + 'px';		
			}
		}		
	}
	table.style.display = 'inline-block';
	table.style.width = size + 'px';
	table.style.height = size + 'px';
	table.style.borderCollapse = 'collapse';
	return table;
}

function fullscreeniconize(el)
{
	var url = "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAIAAAC0Ujn1AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAAEnQAABJ0Ad5mH3gAAAHGSURBVEhL7ZY7isJQFIaTIeKjsnEBgoUoiIW4Cmub2YAbE8RSXYegoCiIa5jGt+B8yTnJJDeJjMOkGJivCJ7Xj7mPc2I/Hg8rG1zp+Xy+Wq1yuZz6fK7Xa7fbrdfrakdZLBbL5TKxqtPpNBoNB2O9Xk+n00KhILGA4/FYqVTSpDebzWQyKRaLavtQVS6XkX7DcBwHXZIMcBKSgjiENC+GVLnSYVifAHWlo3k+6vWJSEv4I8TlcpFQnPP5rEkeeAx1831JGo/Hajzl3UMNy+r3+yyxGh7mgvwiGUvfbjdOzOFw4AkSeBWpRQQQxONeme12u9/v5cSwb71ez0t+jdlsls/n+XG/37kK1Wo1w4v+d7cxI/6lDTKUds8184K+LleGfhZuOt9nOBzKMOEqttttbo0rxxxiXkiAzvcz6dFoJJ3vdDqVSiWk3QVhvjEasGVGeJkvI7UikjxlfpEMpc0pw3oxL+Q3TXIwGKQ1QvaN9eX1xTRGDET+tW3bPEkKkD6ZCNuuSR54pDzAXBDCAepKR/N81OvjSsuUSYS+LnlxnlR9TRm+nna7nZyYMGS0Wi3mhdpRuGWMp/iHGVXNZrNWq2U2ZSzrEyIrIRwRLrUtAAAAAElFTkSuQmCC)";
	el.style.background = url;
	el.style.backgroundPosition = 'center';
	el.style.backgroundRepeat = 'no-repeat';
	el.style.backgroundSize = 'contain';
}

function kostka(size)
{
	var img = newel('img'); img.width = size; img.height = size; img.src =
"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAAEnQAABJ0Ad5mH3gAAABBdEVYdENvbW1lbnQAQ1JFQVRPUjogZ2QtanBlZyB2MS4wICh1c2luZyBJSkcgSlBFRyB2ODApLCBxdWFsaXR5ID0gOTAKfVTa3QAAA7FJREFUSEutl0sodVEUx8/AkGLASEne5f1+TShJMjMg5BEJRWGIZEAmhDD0yJsMDCSECeWRMiNMDDwihZig9X3/fdfe9x73nHuux69O3b323ut/9t5r7bOuRj9gYGCAfHx8yMvLi/r6+tj6PdwWnpqaooCAANI0jUJDQ2l1dZU2NjYoKipK2Pz9/WlycpJHW+NSeG9vj7Kzs4VjPN3d3dzjzODgIHl6eopxWVlZtLu7yz3GOAnf3t5SdXW1EqusrKS7uzvuteb19ZVaWlrUfPgymq+Ee3p61OCMjAza2dnhHhuzs7M0MTHBLfc4PT2lwsJC5RcaEq2hoUEYw8LCaGlpic12np6e1EQ8BQUF3GMD5xwcHEweHh5UXFzMVmcODw8pNzdX+GhsbCQtMzNTOa2vrxdCX+no6KCioiIaHh5mix05F09paSlb9by8vJBcIJ709HTSYmNjaW1tja6vrykvL090IFVmZmZ4mjUPDw/8S8/CwgL5+voKnzk5OXR1dSWOUGRCXFwcjY+P81AbQ0ND6u3Kysro8fGRe6zBjlVUVKj5/f393GMDsSKE4+PjnYQlFxcXahe8vb1penqae5yZn5/XrQ6BZQR2Mjo62iYso7W9vd10dY67UFJSQs/Pz/T29kbl5eXK/nV1RkA4JiaGtISEBJqbm6OjoyPl4P39nYc5c3l5Sfn5+WosIvXs7Ix7rYEw4kpLTExUK5bO3Hnz5uZmqqur45b76IQdzxjb/fHxwS07Nzc3NDIywi2impoaEXjfRW11UlKSaXA5IoMsNTVVtP9EeGxsjM3mbG9vq6MAtbW1PxZGCmvJycmGwicnJ+Tn50f7+/tsIZH8vb294rerFW9tbYkXrKqqYosdS2EEmOMKv+JKWM4LCQlhix0lnJKSos64s7NTl8fy+3p+fs4WO+4ILy8vs8WOThjXGL4ecoJjVN/f3/MvPa6EcSTr6+vc0qOEEaU/yePfRLUQTktL052xWR6jMhkdHeXW74RxTQvhxcVFNpsj8xjfUvBT4ZWVFZswbi5UiAcHB9xlzF/k8fHxMQUGBlJkZCRpn5+fuuIM1YYZyE9ZN31nxV1dXco/yh58hHRJivoJtRcGYCdQ3pphJYwdRMbAV1BQkKjDHTG+Hf6Dr498y7a2NrbaMRPGXSDnYXVGgQpMhSWbm5sUHh4uHCEoZKEOpxAHuFZxA2IMKk7UcFZYCjvS2tqqVoOXwV8Z2W5qaiLEi7t8S1iCFUZERIi72OqvijFE/wDJe4NX3SltrQAAAABJRU5ErkJggg==";
	return img;
}

function flipicon_svgcode()
{
	var svg =
	'<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 286.51758 300.31641" xml:space="preserve" style="transform:rotate(90deg) scaleX(-1)" id="flipicon"><g style="fill:#888"><g transform="matrix(0.8738449,0,0,-0.8738449,18.072831,281.37319)"><path d="M 41.033691,9.1616211 L 60.586426,34.762695 C 23.724609,63.394043 0,108.13672 0,158.42578 C 0,221.24023 37.012207,275.40137 90.413086,300.31592 C 96.984863,285.57178 105.53564,266.39062 113.70996,248.06641 C 72.289551,231.62988 43,191.19922 43,143.92578 C 43,110.79297 57.391602,81.025391 80.258789,60.519531 L 98.132324,83.921387 L 130.51807,0 L 41.033691,9.1616211 z" /><path d="M 245.48389,291.15479 L 225.93115,265.55371 C 262.79297,236.92236 286.51758,192.17969 286.51758,141.89063 C 286.51758,79.076172 249.50537,24.915039 196.10449,0.0004884 C 189.53271,14.744629 180.98193,33.925781 172.80762,52.25 C 214.22803,68.686523 243.51758,109.11719 243.51758,156.39063 C 243.51758,189.52344 229.12598,219.29102 206.25879,239.79688 L 188.38525,216.39502 L 155.99951,300.31641 L 245.48389,291.15479 z" /></g></g></svg>';
	return svg;
}

function svg2src(svg)
{
	//<img src='data:image/svg+xml;utf8,<svg ... > ... </svg>'>
	var div = newel('div'); div.appendChild(svg);
	var svgcode = div.innerHTML;
	var src = 'data:image/svg+xml;utf8,'+encodeURIComponent(svgcode);
	return src;
}

// copied from board.ui
var darkR = 13*16, darkG = 13*16, darkB = 13*16, lightR = 15*16, lightG = 15*16, lightB = 15*16;
function rgb(r,g,b) { return 'rgb('+r+','+g+','+b+')'; }
function darksquarecolor() { return rgb(darkR,darkG,darkB); }
function lightsquarecolor() { return rgb(lightR,lightG,lightB); }

function svgmeridawhatoncolor(what,color)
{
	var svg = svgmerida(what);
	svg.style.backgroundColor = color;
	return svg;
}

function facebookicon()
{
	var code = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"	 width="266.893px" height="266.895px" viewBox="0 0 266.893 266.895" enable-background="new 0 0 266.893 266.895"	 xml:space="preserve"><path id="Blue_1_" fill="#3C5A99" d="M248.082,262.307c7.854,0,14.223-6.369,14.223-14.225V18.812	c0-7.857-6.368-14.224-14.223-14.224H18.812c-7.857,0-14.224,6.367-14.224,14.224v229.27c0,7.855,6.366,14.225,14.224,14.225	H248.082z"/><path id="f" fill="#FFFFFF" d="M182.409,262.307v-99.803h33.499l5.016-38.895h-38.515V98.777c0-11.261,3.127-18.935,19.275-18.935	l20.596-0.009V45.045c-3.562-0.474-15.788-1.533-30.012-1.533c-29.695,0-50.025,18.126-50.025,51.413v28.684h-33.585v38.895h33.585	v99.803H182.409z"/></svg>';
	var div = newel('div'); div.innerHTML = code;
	return div.firstChild;
}

function shareiconimg()
{
	var img = newel('img');
	var share = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB0AAAAgCAYAAADud3N8AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAAEnQAABJ0Ad5mH3gAAANeSURBVEhLrVdNKHRRGL6KZmPDqLFQNmRhMxsWSnaKIgtZSBlqGH8LW/8pFsoWYYXCzGL2MzuzoGyUzIKUn4QkJca/V8/re0/n3LnlM+5TT+ac573PM/fMue89rFQqRW7z9fWVdLy9vRm69fj4SG4RhsDt7S1NTk5SIBCgkZEROjs743noqHP1ToGBgQGyLCuNzc3NrKPOtVBgcHBQhRQVFVFnZyeVlJSouaamJq5zJfTl5YUeHh6UOZZWx9zcnNJOT0/dCQVmZ2fZ1Ov18tiul5aWsj4+Pu5e6NDQEJvqv52ud3V1sY4l/1MolhXY2toij8fDpgUFBTyn1wFlZWWsDw8PZx4qqKmpYTOdU1NT/9RvLC4uKu3g4CA99Pn5mR9m/LVrIDRgfn7eCGppaaH29nY1Li4upmAwqO4QrKqq4mtV6MfHB0/gczKZpPf3dx5LN3l6euLx1dUVFRYWKiNwZ2eHNaCxsdHQhBUVFazDh0OB/f198vl8RiGesfPzc9aB/v5+Q8dzKZAvD2AJu7u7qba2ljo6Omh7e5vnEYgaC4NYLGaY2bmysmKM8eDf3NwYRjpllQRYRV3nUDErLy+n+/t7LkS/zM/PN8LA5eVl1u1N/De0lpaW2CwrK8swE0iYbALAbvJbWlh7mPb09KQZAvJIjI6O8ljXM6XV19fHpq2trWmmgN/vdz90c3OTTUGBbISLiwulTU9P8xxe0E5Gv6GxkcCNjQ06PDykhYUFYx7ERsNLGHAy+19an5+fdHx8nBagMxQKGeOxsTEOxrVOpj9RNQfcQV1dnWGO1iY4Ojqi3NxcpeXk5NDe3h5resvEZyfg5WCEgvbDlECKpU3OzMwYX6yhoYHnAdkLvb29lJ2drWra2tp4XlYmreGDTl1GCMC8srLSCF9bW2NNn7MTfRvXOob+RHmPxuNxR3NwdXWVTk5OKBwOG/NARqFCgX2jSSuV5cayihaNRv8WCsorb2Jigk2rq6t5rNcAOAlC5yOqLmZKQI6f9fX1PLbr2EzQ+ayki5kSWF9fZ1MQkJ0qu140Po7aDTKhLLEY43CGTQRcXl7y0UU0wJVQEMAJQcydGIlEuM7Vf6AAnK/y8vKMMDSKRCLBOupcu1Oh/IZ3d3e0u7tL19fXPAa+a1L0BXbFvPzYDwBOAAAAAElFTkSuQmCC';
	img.src = share;
	return img;
}

function analyzethisimg()
{
	var src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMAAAADACAMAAABlApw1AAAA6lBMVEVaWlKYl4n////99vZYpSyjCwBfpDWCnGnsy8nepKFpoUWNmnjZ5MrR4MC+16ns7uGszpO/VE2RwXJ0s0/Oe3WIm3KAnGWGm26QmX1eVk6UmIORHhSFKyGdEQdhUkpqSUDpwb735+fz8elnTENnrD6BuV54OTD03t3luLWvKiF1n1a8TUW4QTnGaGGrIBZ0PTTU1M68u7KqqZ1kZF12dnCUlI7r6+qFhX/FxLyiop2LJRtkoj367u2YFw2AMSe2059woE+gyIR9NCrXko15nlxvQzrTioTNeXPCXVaUHBFyn1KzNCuIKB7i6dV2YRJZAAAF1ElEQVR4nO3deVebTBQHYBIg7nVr1ey+jSbRqNkU22iibVPXvt//63SAxrDMHQLcCXDO/f1TTnOM94EJmFlAyWc8StIFxA0Bkg4Bkg4Bkg4Bkg4Bkg4EMHr9QQ4jCkKG1zd9IxTA6KMUjwWwcttbGGDg7HtkACPwjgIH0EMsHxWgDPuLAL6h1o8KUJTvwQDk+pEBfoEXgNt+8AGKtxV5AAZ2/eiAoSEEYJ5/5ACUWxEA/wDgA5SeAIB3/ZIIuIUBEg6ABIBigAD0U5AcQB8ESGhBMgA3IAD/HCQFcA0CgorZ+MLNUWRAse5NbQHAMBqg9FbQgNwdRALURro/o2KwIBLgECzfIpTCAxpVTv26PpEDWBGVLxKAVRTH3Pr1sRTAF7PI061t1Z+vmz8sQVjAT1bs7uq9O2u6XpUCuGMlPnCqt7N5wl6ehgS86vrFvfedZAEOWYE7YP1MwF4vhASwT8Av3xvJArxp2omgflXdY4KNFAPWNe2HELDFACuhm1BnmYBPQsB2eMCEfYgvW8sGPJz4EhVQsy4DV7M8LQew478CRAUoddeF7HP2AEqjnAzgYJ5pLAAjdO2Mlwpw/NFwFBMwS5kABCAAAQhAAAIQgABzQLEo6t9KPaD7avYPTUBD2gF/Zl1cUEdpygGTeScdcAzSDbC+KT8dr12wf39mFXDMNu/fwX7S1AM6H9sZBVxa220CEIAABCAAAWQApnidu/WmnfFSAWjd67+dI8UZBDRd49tLAiAOMdXNUqtns9hDTG1r+1keAEoEgDnT48k30u0IMgB9mLWq68/C98QEnOMPdBdZee2lASRMNQgGYH6hWWSyBzDpSdSE/CP1znQQv1J+TLf56s92xOk2FbaDX1b/g7L6wl6voAFyU/8FwJUCNHEOBNT4E7acqQIdQ1EAuRX0KWf1IEG1DvxkJECudA5P+uOfQQMA/El/84zAGYzRACwb/IB7PwDAm3Y5j6B3NzIgSoSAiCEAAQhAAAIQgAAEIAAHIOOXydgpBCAAAQhAAAIQgAAEIEDWAI1u05PuAqvHAwElftABtTKnA7zaiAkoTe+gAZNH/phhVACw/v01HkC8Tv8cEWCvfz9z54r9V/BdFAT1B63Tf8MDmNMadr1LZ1djAsyxW23/YZOXh33RMQgPGLHd75sVEBdgth946NaaVgJ8DsIDyrxpATEBB8L6bcE6IuASG7DO2o+gflU1WxEWoKLrV96PQFxAQXwA7EPAb0PhAb9Zre/t1scwOhJgSwjYQgQo7svYLhJg097Tp97s4QNqr/IAe74LwD4+QClOqtkGsDTsjGQAzud5lAb4l4oEgHOy1CEBCEAAAhCAAAQgQJYADeE9KlMPaFp/ZpehSbapB8zWv+vdbAIq8285QD9pygEsV2ut1rPZirIKsJZhmAJ+Z3vqAe8f2/w2lHrAhbXdIgABCEAAAhAg3YBid1KxMpYBcNxLfyoH0HWOdKMD5Hevu9e/Zw/QMEs968xiL+BvWdvPmQCY31t+qYLEAaj+Jb7oAPbBfRHVHxEgvtHAJ0QA90bvMQF3mrYnfM9TzXUblLhH4LPwlx2HB7DT5awNcWO2IGCZeHjAiDdS78xu+K+UJVbgyf/gO1rr9IF1yuEBXfMktHYMpd1hr//h/ygIsA5BYYf3VAxV3bYmqwBzPeKP1PMCrX+HAeZ0D/Mo8GK9UoBmzoGAIQgoBgnA9e8CQOnRdwVwBl6nDwKu4UOgNIGnrtjlV8DeUQGA/eEGzfljux94KAnLAATcCADWTUyhCH5KCMjljlYOuBHMWcwJnoYlBERLACBKeiDAyAYAfqBa/jYLAPeDHd2AXhYAhgAg4RCg1z/IiwAGfClIC0D8YE38ExF2/d6nFHsB+e/pBnzz1usDYAsk188B5PuonwPU+jlPueYA8gbmuQix/MGCj7hm6eER0MoHnpPOB5jPSb+5RmlKKMUP+r1wj3nPUAiQdAiQdAiQdAiQdAiQdDIP+Av88XHZ4glaBwAAAABJRU5ErkJggg==";
	var img = newel('img'); img.src = src;
	img.style.width = '1em'; img.style.height = '1em';
	return img;
}
var GameOverAlert = false;
var transferfen = '0';
var matein = 0;
var encodedlines = '';
var mode = 1;
var ms = '1000';
var Value = '';
var header = '';
var altrules = [];
var niema = '';
var puzzle = '';
var puzzleset = null;
var gallery = null;
var pozadaniu = false;
var successrule = '';
var MateIn = 0;

var query = new URLSearchParams(location.search);
if (query.has('symmetry')) setCookieMinutes('symmetry',query.get('symmetry'),1);
var randomsymmetry = getCookie('symmetry')=='random';

function el(id) { return document.getElementById(id); }
function newel(a) { return document.createElement(a); }

function galogg(action,label) { parent.window.galog(action,label); }

function editorpath() // used only in controlspanel
{
	var apronus = 'https://www.apronus.com/chess/puzzle/editor.php';
	var developer = wizboardpath+'editor.php';
	var isklon = parent.location.href.indexOf('https://agnes-bruckner.com/klon/')==0;
	return (isklon) ? developer : apronus;
}

function ismobile()
{
	return (window.innerWidth < window.innerHeight);
}

/*
function resizepuzzlediv_mobile()
{
 document.body.style.margin = '0';
 var a = document.getElementById('puzzlediv');
 var topp = 0;//0
 a.style.paddingTop = topp+'px'; a.style.height = (window.innerHeight - beforepuzzlediv.clientHeight - (topp+1)) + 'px';
 var left = 3;//3
 a.style.paddingLeft = left+'px'; a.style.width = (window.innerWidth - (left+1)) + 'px';
}
*/
/*
function resizepuzzlediv()
{
	if (ismobile()) { resizepuzzlediv_mobile(); return; }
 document.body.style.margin = '0';
 var a = document.getElementById('puzzlediv');
 a.style.paddingTop = '16px'; a.style.height = (window.innerHeight - beforepuzzlediv.clientHeight - 17) + 'px';
 a.style.paddingLeft = '8px'; a.style.width = (window.innerWidth - 9) + 'px';
}
*/

function resizepuzzlediv()
{
	if (ismobile()) resizepuzzlediv_portrait(); else resizepuzzlediv_landscape();
	setTimeout(g_stateline_refresh,10); // to prevent misplaced arrows
}

function windowresize()
{
	resizepuzzlediv();
}

function init_puzzlediv()
{
 document.body.innerHTML = '';
 var a = newel('div'); a.id = 'beforepuzzlediv'; document.body.appendChild(a);
 var a = newel('div'); a.id = 'puzzlediv'; document.body.appendChild(a);
 insert_puzzle_css();
 //if (ismobile()) insert_mobilepuzzle_css();
 document.body.addEventListener('keydown',keywaspressed);
 document.body.addEventListener('keypress',function(e){ if (e.key=='?') togglecontrols(); });
 document.body.insertBefore(garbols(),puzzlediv); // garbochess purely technical controls are invisible
 document.body.insertBefore(controlspanel(editorpath()),puzzlediv); // positioned, initially invisible
 window.addEventListener("resize", windowresize );
 window.addEventListener('beforeunload', function (e) {
	if (el('controls').style.display == 'none') return;
	el('controls').style.display = 'none';
	e.preventDefault();
	e.returnValue = '';
});

}


function initialize_globals_from_query(q)
{
 puzzle = ''.concat(q || location.search);
 var query = new URLSearchParams(puzzle);
      if (query.has('p'))   transferfen = query.get('p');
 else if (query.has('fen')) transferfen = query.get('fen');
 else                       transferfen = '0rnbqkbnrXppppppppX8X8X8X8XPPPPPPPPXRNBQKBNR_w_KQkq_-_0_1';
 matein = parseInt( (query.has('N')) ? query.get('N') : '0' );
 MateIn = parseInt( (query.has('M')) ? query.get('M') : '0' );
 if (MateIn > 0) matein = MateIn;
 encodedlines = (query.has('w')) ? query.get('w') : '';
 mode = parseInt( (query.has('m')) ? query.get('m') : '1' );
 Value = (query.has('V')) ? query.get('V') : '';
 header = (query.has('h')) ? query.get('h') : '';
 altrules = findrules(parsujzakodowanewarianty(encodedlines));
 if (!query.has('h') && matein>0)
 {
  var tomove = (transferfen.indexOf('_w_')>-1) ? 'White' : 'Black';
  var ruchach = (matein == 1) ? '1 move' : (matein+' moves');
  header = "<h1 id='tomoveandwin'>" + tomove + ' to move and win by checkmate in ' + ruchach + "</h1>";
  header = '<h3>'+tomove+' mates in '+matein+'.</h3>';
 }
 niema = '';
 if (query.has('niemaK')) niema += 'K';
 if (query.has('niemaR')) niema += 'R';
 if (query.has('niemaN')) niema += 'N';
 if (query.has('niemak')) niema += 'k';
 if (query.has('niemar')) niema += 'r';
 if (query.has('nieman')) niema += 'n';
 if (query.has('niema')) niema = query.get('niema');
 if (query.has('ms')) { ms = parseInt(query.get('ms')); if (isNaN(ms) || ms < 10) ms = 1000; } else { ms = '1000'; }
 successrule = (query.has('su')) ? query.get('su') : '';
 if (encodedlines == '' && successrule == '') pozadaniu = true;
 
 if (query.has('chesthetica')) header += chesthetica_header_html(query.get('chesthetica'));
 if (query.has('chesthetica') && !query.has('ms')) ms = 3000;
}

function g_puzzle_state()
{
	if (el('edytorek')) return 'editor';
	if (encodedlines == 'pgnviewer') return 'nopuzzle';
	if (encodedlines == '' && successrule == '') return 'nopuzzle';
	if (!pozadaniu) return 'runningpuzzle';
	return 'puzzle';
}

function g_puzzle_czyselfplay()
{
	return (mode & 64) == 64;
}
function g_puzzle_setselfplay(czy)
{
	if ((mode & 64) == 0) mode += czy ? 64 : 0; else mode -= czy ? 0 : 64;
}

function g_puzzle_czy_allow_engine_help()
{
	return 0 === (mode & 512);
}
function g_puzzle_czy_selfplay_exam()
{
	return mode & 1024;
}


function load_puzzle(query,exam)
{
	if (query.indexOf('wizboardpgnviewer')>-1) { load_puzzle_pgnviewer(query); return; }
 pozadaniu = false;
 initialize_globals_from_query(query);
 set_fen_flipcyfra_from_transferfen(transferfen);
 SetControls();
 var a = el('PgnTextBox'); if (a) a.value = ''; // to prevent restart puzzle bug because redrawpuzzlediv calls resetgarbochess
 redrawpuzzlediv();
 puzzleindexdocumentready();
 el('controls_showme').style.display = (encodedlines == '') ? 'none' : 'inline';

 if (!exam && g_puzzle_czy_selfplay_exam())
 {
	 stoppuzzle();
	 setTimeout(resizepuzzlediv,0);
	 return;
 }
 if (exam && g_puzzle_czy_selfplay_exam())
 {
	 g_stateline_refresh();
	 setTimeout(resizepuzzlediv,0);
	 return;
 }
 resizepuzzlediv();
}

function editpuzzlewindow(prefix)
{
	window.open(prefix + puzzle);
}
/*function editpuzzlewindow(prefix)
{
 var pu = replace(puzzle,'p=','fen=');
 window.open(prefix+pu+'&editpuzzle=1');
}*/
/*
function editpuzzlewindow(prefix)
{
	var pu = replace(puzzle,'p=','fen=');
	var url = prefix+pu+'&editpuzzle=1';
	{
		var a = window.open();
		
		var doc = '';
		doc += '<html lang='+detectlanguage()+'>'; //doc += (isPolish()) ? "<html lang=pl>\n" : "<html lang=en>\n";
		doc += '<head>\n';
		doc += '<meta charset="UTF-8">\n';
		doc += '<meta name="viewport" content="width=device-width, initial-scale=1">\n';
		doc += '</head>\n';
		a.document.write(doc);
		
		a.document.write('<scr'+'ipt>function galog(a,b) { console.log("nothing: galog("+a+","+b+")"); }</sc'+'ript>');
		a.document.write("<body style='margin:0;padding:0;'></body>");
		var ifr = newel('iframe');
		ifr.style.border = 'none';
		ifr.src = url;
		ifr.style.width = '100%';
		ifr.style.height = window.innerHeight + 'px';
		a.document.body.appendChild(ifr);
		a.document.close();
	}
}
*/

const apply1024topuzzlequery = (puzzle) =>
{
	const tip = () =>
	{
		const div = newel('div');
		div.id = 'selfplayexamtip';
		div.innerHTML = selfplayexamtiptext();
		div.style.backgroundColor = '#fdd';
		div.style.fontWeight = 'normal';
		div.style.textAlign = 'center';
		div.style.padding = '4px';
		return div.outerHTML;
	}
 const query = new URLSearchParams(puzzle);
 let m = 1;
 if (query.has('m'))
 {
  m = parseInt(query.get(m));
  if (isNaN(m)) m = 1;
  query.delete('m');
 }
 const new_m = (m & 1024) ? m : (m + 1024);
 query.append('m',new_m);
 
 if (query.has('h'))
 {
	 let h = query.get('h');
	 h += tip();
	 query.set('h',h);
 }
 return '?' + query.toString(); 
}

const stoppuzzle = () =>
{
 const restartbutton = () =>
 {
  const bu = newel('button');
  bu.id = 'gotoexambutton';
  bu.innerHTML = gotoexamtext();
  bu.style.outline = 'thick solid #fdd';
  bu.onclick = function(){ load_puzzle(puzzle,'exam'); };
  return bu;
 }
 g_puzzle_setselfplay(true);
 pozadaniu = true;
 nocomp.checked = true;
 engineon.checked = false;
 controls.style.display = "none";
 if (el('showme_animatton')) showme_animatton.disabled = true;
 if (el('showme_animatton')) showme_animatton.innerHTML = '';
 if (el('showme_animatton')) showme_animatton.style.visibility = 'hidden';
 if (el('gotoexambutton') === null) belowboardbuttons.prepend(restartbutton());
 resizepuzzlediv();
 if (el('showme_animatton')) showme_animatton.style.padding = '0';
 if (el('showme_animatton')) showme_animatton.style.border = 'none';
 g_stateline_refresh();
 if (el('showme_animatton')) showme_animatton.style.display = 'none';
 setTimeout(resizepuzzlediv,0);
}

function controlspanel(editorpath)
{
	function closecontrolsbutton()
	{
	 var aa = newel('button'); aa.innerHTML = 'OK';
	 aa.setAttribute('onclick','controls.style.display="none";');
	 aa.id = 'OKcontrolsbutton';
	 return aa;
	}
	function showme_button()
	{
		var bu = newel('button'); bu.id = 'controls_showme';
		bu.innerHTML = AnimateSolutionText();
		bu.setAttribute('onclick','controls.style.display="none";showAnimacja();');
		return bu;
	}
	function restartpuzzlebutton()
	{
	 var aa = newel('button'); aa.innerHTML = restartpuzzletext();
	 aa.setAttribute('onclick','load_puzzle(puzzle); controls.style.display="none";');
	 aa.style.marginRight = '1em';
	 return aa;
	}
	function stoppuzzlebutton()
	{
	 var aa = newel('button'); aa.innerHTML = stoppuzzletext();
	 aa.onclick = stoppuzzle; 
	 aa.style.margin = '1em';
	 return aa;
	}	
	function showsolutioncheckbox()
	{
	 var aa = newel('input'); aa.type = 'checkbox'; aa.id = 'showinsight'; aa.setAttribute('onchange','toggleinsight();');
	 var p = newel('p'); p.appendChild(aa); p.innerHTML += showsolutiontext(); return p;
	}
	function wrongmovealertcheckbox()
	{
	 var aa = newel('input'); aa.type = 'checkbox'; aa.id = 'wrongmovealertbox'; aa.setAttribute('checked','checked');
	 var p = newel('p'); p.appendChild(aa); p.innerHTML += wrongmovealerttext(); return p;
	}
	function altcheckbox()
	{
	 var aa = newel('input'); aa.type = 'checkbox'; aa.id = 'altbox';
	 var p = newel('p');
	 p.appendChild(aa); p.innerHTML += requireallalternativesolutionstext(); return p;
	}
	function bothcheckbox()
	{
	 var aa = newel('input'); aa.type = 'checkbox'; aa.id = 'bothbox';
	 var p = newel('p'); p.appendChild(aa); p.innerHTML += userplaysbothsidestext(); return p;
	}
	function blindfoldcheckbox()
	{
	 var aa = newel('input'); aa.type = 'checkbox'; aa.id = 'blindfold'; aa.setAttribute('onchange',"RedrawBoard();");
	 var p = newel('p'); p.appendChild(aa); p.innerHTML += playblindfoldonanemptyboardtext(); return p;
	}
	function passivecheckbox()
	{
	 var aa = newel('input'); aa.type = 'checkbox'; aa.id = 'passivebox';
	 var p = newel('p'); //p.style.display = 'none';
	 p.appendChild(aa); p.innerHTML += thecomputermovesonlywhencheckedorthreatenedwithmatetext();
	 return p;
	}
	function nocompcheckbox()
	{
	 var aa = newel('input'); aa.type = 'checkbox'; aa.id = 'nocomp'; aa.style.display = 'none';
	 var bb = newel('input'); bb.type = 'checkbox'; bb.id = 'engineon';
	 bb.setAttribute('onchange',"document.getElementById('nocomp').checked = !this.checked;");
	 var p = newel('p'); p.appendChild(aa); p.appendChild(bb); p.innerHTML += enginerepliestousermovesinpostmortemanalysistext();
	 p.style.display = 'none';
	 return p;
	}
	function forcetomovepar()
	{
	 var aa = newel('button'); aa.setAttribute('onclick',"forcemove();"); aa.innerHTML = forceenginetomovetext();
	 var p = newel('p'); p.appendChild(aa); p.innerHTML += ' '+orpressspacebarorclickthetriangletext();
         p.id = 'forcetomovep'; return p;
	}
	function millisecspar()
	{
		const set_ms_button = (ilesekund) =>
		{
			const bu = newel('button');
			bu.innerHTML = ilesekund + 's';
			bu.onclick = function(){ el('TimePerMove').value = parseInt(Number(ilesekund)*1000); UIChangeTimePerMove(); a=a; };
			bu.style.marginLeft = '1em';
			return bu;
		}
	 var aa = newel('input'); aa.id = "TimePerMove"; aa.setAttribute('value','1000'); aa.setAttribute('onchange',"UIChangeTimePerMove();");
	 aa.style.marginRight = '0'; aa.style.width = '4em';
	 aa.type = 'number'; aa.setAttribute('min','100');
	 var p = newel('p'); p.innerHTML = millisecondspercomputermovetext()+' '; p.appendChild(aa); p.innerHTML += 'ms';
	 p.append(set_ms_button(0.1), set_ms_button(1), set_ms_button(3), set_ms_button(7));
	 return p;
	}
	function editpuzzlebutton(prefix)
	{
	 var bu = newel('button'); bu.innerHTML = editpuzzleinnewwindowtext();
	 bu.setAttribute('onclick','editpuzzlewindow("'+prefix+'");');
	 bu.style.display = 'block'; bu.style.marginTop = '1em'; bu.style.marginBottom = '1em'; return bu;
	}
	function analyzebutton(editorpath)
	{
		editorpath = 'https://www.apronus.com/chess/puzzle/editor.php';
	 var bu = newel('button'); bu.innerHTML = analyzeinnewwindowtext();
	 bu.setAttribute('onclick','window.open(thischessboardURL("'+editorpath+'"));');
	 bu.style.display = 'block'; bu.style.marginTop = '1em'; bu.style.marginBottom = '1em'; return bu;
	}
	function apronuslink()
	{
		var a = newel('a'); a.id = 'apronuslink'; a.target = '_blank';
		a.innerHTML = 'share link to this puzzle';
		return a;
	}
	function FENcontrol()
	{
	 var aa = newel('input'); aa.id = 'FenTextBox'; aa.style.width = '100%'; aa.setAttribute('onclick',"this.select();");
	 var p = newel('div'); p.innerHTML = 'FEN<br/>'; p.appendChild(aa); return p;
	}
	function lichessanalyzebutton()
	{
		var bu = newel('button');
		bu.innerHTML = 'analyze at lichess.org';
		bu.addEventListener('click', function(e){ window.open('https://lichess.org/analysis/' + el('FenTextBox').value.split(' ').join('_')); });
		return bu;
	}
	function analyzethisbutton()
	{
		var bu = newel('button'); bu.innerHTML = 'Analyze This '; bu.appendChild(analyzethisimg());
		bu.addEventListener('click', function(e){ window.open('https://www.newinchess.com/analyze/?fen=' + encodeURIComponent(el('FenTextBox').value)); });
		return bu;
	}
	function syzygytablesbutton()
	{
		var bu = newel('button'); bu.innerHTML = 'analyze at syzygy-tables.info';
		bu.addEventListener('click', function(e){ window.open('https://syzygy-tables.info/?fen=' + el('FenTextBox').value.split(' ').join('_')); });
		return bu;
	}
	function yacpdbbutton()
	{
		var bu = newel('button'); bu.innerHTML = 'look up at yacpdb.org';
		bu.addEventListener('click', function(e)
		{
			var fenpieces = (el('FenTextBox').value.split(' ')[0]).split('/').join('');
			window.open('https://www.yacpdb.org/#search/' + window.btoa(fenpieces + '/////////////1/1/1/0') + '/1');
		});
		return bu;
	}
	function FENbuttons()
	{
		var div = newel('div'); div.id = 'fenbuttonsdiv';
		div.appendChild(lichessanalyzebutton());
		div.appendChild(analyzethisbutton());
		div.appendChild(syzygytablesbutton());
		div.appendChild(yacpdbbutton());
		var bu = div.querySelectorAll('button');
		for (var i=0; i < bu.length; i++) bu[i].style.margin = '3px 1em 0 0';
		return div;
	}
	function FENpanel()
	{
		var div = newel('div');
		div.appendChild(FENcontrol()); div.appendChild(FENbuttons());
		div.style.margin = '1em 0 1em 0';
		return div;
	}
	const copytext_panel = () =>
	{
		const copymoves_button = () =>
		{
			const bu = newel('button');
			bu.innerHTML = 'copy moves';
			bu.onclick = function()
			{
				const copyText = el('copytextarea');
				copyText.select();
				copyText.setSelectionRange(0, 99999); 
				navigator.clipboard.writeText(copyText.value);
			};		
			return bu;
		}
		const textbox = () =>
		{
			const text = newel('textarea');
			text.id = 'copytextarea';
			text.style.display = 'block';
			text.style.width = '100%';
			text.style.margin = '0.5em 0 0 0';
			return text;
		}
		const div = newel('div');
		div.style.margin = '1em 0 1em 0';
		div.append(copymoves_button(), textbox());
		return div;
	}

 var controls = newel('div'); controls.id = 'controls';
 controls.style.display = 'none'; // block when visible
 controls.style.margin = '0';
 controls.style.padding = '1em';
 controls.style.background = 'white';
 controls.style.color = 'black';
 controls.style.border = 'thick groove lightgray';
 controls.style.position = 'absolute';
 controls.style.left = '5%'; controls.style.right = '5%';
 controls.style.top = '5%'; controls.style.bottom = '5%';
 controls.style.zIndex = '7'; 
 controls.style.maxHeight = window.innerHeight * 0.9 + 'px';
 controls.style.overflow = 'auto';
 controls.appendChild(closecontrolsbutton());
 controls.appendChild(newel('br'));
 controls.appendChild(showme_button());
 controls.appendChild(stoppuzzlebutton());
 controls.appendChild(restartpuzzlebutton());
 controls.appendChild(fullscreenbutton(togglefullscreentext()));
 controls.appendChild(newel('hr'));
 controls.appendChild(showsolutioncheckbox());
 controls.appendChild(wrongmovealertcheckbox());
 controls.appendChild(altcheckbox());
 controls.appendChild(bothcheckbox());
 controls.appendChild(blindfoldcheckbox());
 controls.appendChild(passivecheckbox());
 controls.appendChild(newel('hr'));
 controls.appendChild(nocompcheckbox());
 controls.appendChild(forcetomovepar());
 controls.appendChild(millisecspar());
 controls.appendChild(newel('hr'));
 controls.appendChild(editpuzzlebutton(editorpath));
 controls.appendChild(analyzebutton(editorpath));
 controls.appendChild(newel('hr'));
 controls.appendChild(FENpanel());
 controls.appendChild(newel('hr'));
 controls.append(copytext_panel());
 controls.appendChild(apronuslink());
 if (puzzleset) controls.appendChild(animasetform(puzzleset));
 return controls;
}

function togglecontrols()
{
 var a = document.getElementById('controls');
 var seen = (a.style.display == 'block');
 a.style.display = seen ? 'none' : 'block';
 if (a.style.display === 'block')
 {
   el('OKcontrolsbutton').focus();
   const line = el('PgnTextBox').value;
   const blackstarts = fen.includes(' b ');
   el('copytextarea').value = ponumeruj(line,blackstarts);
 }
}

function toggleinsight()
{
 if (document.getElementById('showinsight').checked)
 {
  document.getElementById('insight').style.display = 'inline';
  showinsight(zadanie);
 }
 else { document.getElementById('insight').style.display = 'none'; }
}

function SetControls()
{
 var m = Number(mode);
 var wrong = (m & 1) == 1;
 var all = (m & 2) == 2;
 var blindfold = (m & 4) == 4;
 var solution = (m & 8) == 8;
 var both = (m & 16) == 16;
 var passive = (m & 32) == 32;
 var selfplay = (m & 64) == 64;
 
 document.getElementById('wrongmovealertbox').checked = wrong;
 document.getElementById('altbox').checked = all;
 document.getElementById("blindfold").checked = blindfold;
 document.getElementById('showinsight').checked = solution;
 document.getElementById('bothbox').checked = both;
 document.getElementById('passivebox').checked = passive;
 el('engineon').checked = !selfplay; el('nocomp').checked = selfplay;
 el("TimePerMove").setAttribute('value',ms); UIChangeTimePerMove();
 
 el('apronuslink').href = apronussharerURL();
 if (el('f')) el('facebooksharer').parentElement.removeChild(el('facebooksharer'));
 el('controls').appendChild(facebooksharerbutton(puzzle));
 if (!g_puzzle_czy_allow_engine_help())
 {
  el('forcetomovep').style.display = 'none';
  el('fenbuttonsdiv').style.display = 'none';
 }
}

function animatton()
{
 var bu = newel('button'); bu.setAttribute('onclick','showAnimacja();'); bu.innerHTML = AnimateSolutionText();
 bu.style.display = 'block'; bu.style.marginTop = '1em'; bu.style.marginBottom = '1em'; 
 bu.id = 'showme_animatton'; return bu;
}

function garbols()
{
 var span = newel('span'); span.id = 'output';
 var txt = newel('textarea'); txt.id = 'PgnTextBox'; txt.setAttribute('cols','50'); txt.setAttribute('rows','6'); txt.style.display = 'block';
 var a = newel('a'); a.id = 'AnalysisToggleLink'; a.href = "javascript:UIAnalyzeToggle()"; a.innerHTML = 'toggle analysis';
 var div = newel('div'); div.appendChild(a); div.appendChild(txt); div.appendChild(span);
 div.style.display = 'none'; return div;
}
//function resetgarbols() { el('output').innerHTML = ''; el('PgnTextBox').innerHTML = ''; }

function NewWindowStartButton()
{
 var bu = newel('button'); bu.innerHTML = 'New Window Start';
 bu.setAttribute('onclick',"window.open(location.href);");
 return bu;
}

/*
function ControlsButton()
{
 var bu = newel('button'); bu.innerHTML = 'controls ';
 bu.appendChild(settingsicon(12)); bu.style.cursor = 'pointer';
 bu.setAttribute('onclick','togglecontrols();');
 return bu;
}
*/

// ==========================================

function applyfieldsetstyle(a)
{ a.style.border = 'none'; a.style.display = 'inline'; a.style.verticalAlign = 'top'; a.style.padding = '0'; a.style.margin = '0'; }

function addcontrolstd()
{
 //return; var con = document.createElement('input'); con.type = 'checkbox';
 var con = settingsicon(16,'#888888','white');
 con.id = 'showcontrols';
 con.setAttribute('onclick','togglecontrols(); galogg("settings","-");');
 con.style.margin = '0';
 con.style.padding = '0';
 con.style.display = 'block';
 var controlstd = document.getElementById('controlstd');
 controlstd.innerHTML = '';
 controlstd.style.padding = '0';
 controlstd.style.textAlign = 'right';
 controlstd.style.verticalAlign = 'bottom';
 controlstd.appendChild(con);
 //controlstd.className = 'spinning';
}


function alertfield()
{
	if (el('savedalert'))
	{
		var saved = el('savedalert').cloneNode(true);
		if (saved.innerHTML)
		{
			el('savedalert').innerHTML = '';
			return saved;
		}
	}
	return alertpanel();
}

function alertpanel()
{
	function closealertbutton()
	{
		var zamknij = newel('button'); zamknij.id = 'closealertbutton';
		zamknij.innerHTML = 'X'; //'&#10060;';
		zamknij.setAttribute('onclick','clearalert();');
		zamknij.style.float = 'right'; zamknij.style.verticalAlign = 'top';
		zamknij.style.visibility = (ismobile()) ? 'visible' : 'hidden';
		return zamknij;
	}
	function limitOKbutton()
	{
		var bu = newel('button');
		bu.setAttribute('onclick',"startfen(zadanie);galogg('limit-OK-click','-');");
		bu.className = 'limitOKbutton'; // makes it flash
		bu.style.height = '45px'; bu.style.width = '60px';
		bu.style.verticalAlign = 'top';
		applySVGbackground(bu,resigntriangle());
		return bu;
	}
	var aa = newel('fieldset'); aa.id = 'alert'; applyfieldsetstyle(aa);
	var correct = newel('fieldset'); applyfieldsetstyle(correct); correct.id = 'correct';
	correct.appendChild(correctimgdiv());
	var percentage = newel('fieldset'); percentage.id = 'percentage'; correct.appendChild(percentage);
	applyfieldsetstyle(percentage); percentage.style.fontSize = '32px'; percentage.style.fontFamily = 'Arial';
	aa.appendChild(correct);
	var keepon = newel('span'); keepon.id = 'keeponsolving';
	var nextbutton = newel('button'); nextbutton.innerHTML = NEXTbuttonText(); nextbutton.className = 'nextlinebutton';
	nextbutton.setAttribute('onclick',"startfen(zadanie);");
	nextbutton.style.height = '45px'; nextbutton.style.verticalAlign = 'top';
	keepon.appendChild(nextbutton);
	aa.appendChild(keepon);
	var juzkoniec = newel('div'); juzkoniec.id = 'juzkoniec';
	juzkoniec.appendChild(finishimgdiv());
	aa.appendChild(juzkoniec);
	var wrongmove = newel('p'); wrongmove.id = 'wrongmovealert';
	var wrong1 = newel('span'); wrong1.appendChild(wrongimgdiv());
	wrongmove.appendChild(wrong1);
	var back = newel('button'); back.innerHTML = TAKEBACKbuttonText();
	//back.setAttribute('onclick','takebackwrongmove();');
	back.setAttribute('onclick',"galogg('wrongtakeback',ilemoves());cofnijruch();");
	back.style.height = '45px'; back.style.verticalAlign = 'top';
	wrongmove.appendChild(back);
	aa.appendChild(wrongmove);
	var limit = newel('p'); limit.id = 'limitalert';
	var wrong2 = newel('span'); wrong2.appendChild(wrongimgdiv());
	limit.appendChild(wrong2);
	var limittext = newel('span'); limittext.innerHTML = LimitReachedText();
	limittext.style.display = 'inline-block'; limittext.style.background = 'white';
	limittext.style.height = '45px'; limittext.style.verticalAlign = 'top';
	limittext.style.paddingLeft = '4px'; limittext.style.paddingRight = '4px';
	limit.appendChild(limittext);
	limit.appendChild(limitOKbutton());	
	aa.appendChild(limit);
	aa.style.display = 'none';
	aa.style.maxHeight = '45px';
	aa.style.outline = '3px solid white';
	aa.appendChild(closealertbutton());
	if (puzzleset)
	{
		var n = puzzleset_currentnumber();
		if (n < puzzleset_lastnumber()) juzkoniec.appendChild(nextpuzzlebutton(n));
		                           else juzkoniec.appendChild(nextsetlink());
	}
	return aa;
}

function append_outputboard( element )
{
 var outputboard = newel('fieldset'); outputboard.id = 'outputboard'; applyfieldsetstyle(outputboard);
 var movelistfield = newel('fieldset'); movelistfield.id = 'movelist'; applyfieldsetstyle(movelistfield);
 outputboard.appendChild(movelistfield);
 outputboard.appendChild(alertfield());
 element.appendChild(outputboard);
 clearalert();
 el('alert').style.display = 'inline-block';
 applynoselect(outputboard);
}

function append_solution(element)
{
 var f = document.createElement('fieldset');
 f.id = 'solutionfieldset'; applyfieldsetstyle(f);
 f.innerHTML += "<fieldset id='insight'></fieldset>";
 element.appendChild(f);
}

function autocorrectheader(hh)
{
	var aa = hh.querySelectorAll('a');
	if (aa.length > 0)
	{
		for (var i = aa.length-1; i >= 0; i--)
		{
			aa[i].setAttribute('target','_blank');
			aa[i].setAttribute('rel','noopener');
		}
	}
}

function redrawpuzzlediv()
{
 var movelistsafe = el('movelist'); if (movelistsafe) movelistsafe = movelistsafe.cloneNode(true);
 el('puzzlediv').innerHTML = '';
 
 var prepuzzle = newel('div'); prepuzzle.id = 'prepuzzle';
 var h1header = newel('div'); h1header.id = 'h1header'; h1header.innerHTML = header;
 autocorrectheader(h1header);
 styleh1header(h1header);
 prepuzzle.appendChild(h1header);
 if (ismobile() && g_puzzle_state() != 'nopuzzle')
 {
	 prepuzzle.style.minHeight = '45px';
 }
 el('puzzlediv').appendChild(prepuzzle);
 
 var f = newel('fieldset'); f.id = 'chessboard'; applyfieldsetstyle(f); el('puzzlediv').appendChild(f);
 var w = el('puzzlediv').clientWidth;
 var h = el('puzzlediv').clientHeight;
 var margin = Math.floor(h*0.10);
 h = h - margin;
 h = h - el('prepuzzle').clientHeight;
 var size = (h<w) ? h : w;
 var gap = 10;
 if (ismobile()) size -= 20+gap; // to make sure that [show me] [resign] [take back] are visible below the board
 appendboard(el('chessboard'),size);
 if (flipcyfra=='1') h8a1(); RedrawBoard(); updateczyjruch(); addcontrolstd();

 var aa = newel('fieldset'); applyfieldsetstyle(aa); aa.id = 'BelowOrBesideBoard';
 el('puzzlediv').appendChild(aa);
 //aa.style.marginTop = gap + 'px'; // gap between board and aa

 var bbb = newel('div'); bbb.id = 'belowboardbuttons'; aa.appendChild(bbb);
 //bbb.appendChild(facebooksharerbutton(puzzle));
 bbb.appendChild(FullScreenButton());
 var showmebutton = animatton();
 showmebutton.style.display = 'inline'; showmebutton.style.marginRight = '1em';
 if ((mode & 256) == 0) if (encodedlines || MateIn>0) bbb.appendChild(showmebutton);
 bbb.appendChild(ResignButton()); bbb.appendChild(BackButton());
 bbb.appendChild(GoLeftButton()); bbb.appendChild(GoRightButton());
 hidebackbuttons();
 
 //if (!ismobile()) aa.appendChild(newel('br'));
 
 append_outputboard(aa); applynoselect(aa);
 append_solution(aa); 
 el('chessboard').style.display = 'inline';
 el('chessboard').style.marginRight = '1em';
 el('outputboard').style.width = (ismobile()) ? '100%' : '19em'; 
 el('outputboard').style.padding = '0';
 el('outputboard').style.lineHeight = '1.5';
 el('movelist').style.maxHeight = (el('chessboard').clientHeight/2) + 'px';
 el('movelist').style.overflow = 'visible';
 el('movelist').style.display = 'block';
 el('movelist').style.lineHeight = '1.5';
 el('insight').style.width = '23em';
 el('insight').style.height = '90%';
 el('insight').style.position = 'absolute'; el('insight').style.zIndex = '7';
 el('insight').style.right = '0';
 el('insight').style.top = el('beforepuzzlediv').clientHeight+'px';
 updatemoves_clear();
 /*updatemoves();
 if (movelistsafe) 
 {
	 el('movelist').parentElement.replaceChild(movelistsafe,el('movelist'));
	 g_stateline_refresh();
 }
 //var ruchy = document.getElementById('PgnTextBox').value; if (zadanie) resetgarbochess(sameruchyarray(ruchy));
 */
 
 if (zadanie) showinsight(zadanie);
 el('insight').style.display = (el('showinsight').checked) ? 'inline' : 'none';
 
 var gallery = newel('div'); gallery.id = 'gallery';
 gallery.style.display = 'inline-block';
 gallery.style.width = (w - 30 - el('chessboard').clientWidth - el('outputboard').clientWidth) + 'px';
 gallery.style.overflow = 'auto';
 gallery.style.height = el('chessboard').clientHeight + 'px';
 el('puzzlediv').appendChild(gallery);
 if (ismobile())
 {
  el('puzzlediv').style.position = 'relative';
  el('alert').style.position = 'absolute';
  el('alert').style.top = '0'; el('alert').style.left = '0';
  el('alert').style.background = '#eee';
  el('alert').style.width = '100%';
 }
 if (randomsymmetry) symmetrizeh1header();
  var a = el('ResultPanel'); if (a) { a.parentElement.removeChild(a); }
 setTimeout(function(){resizegallery(w);}, 0);
}

function resizegallery(w)
{
 var gallery = el('gallery');
 gallery.style.width = (w - 30 - el('chessboard').scrollWidth - el('outputboard').scrollWidth) + 'px';
 gallery.style.height = el('chessboard').scrollHeight + 'px';
}

// puzzleset object uses global variable puzzleset

function puzzleset_clean()
{
 if (puzzleset.length < 2) return;
 for (var n=1; n < puzzleset.length; n++)
  puzzleset[n] = replace(puzzleset[n],'&amp;','&');
}

function puzzleset_currentnumber()
{
 return Number(puzzleset[0]);
}

function puzzleset_setcurrentnumber(n)
{
 puzzleset[0] = n;
}

function puzzleset_lastnumber()
{
 return puzzleset.length - 1;
}

function nextpuzzlebutton(n)
{
 var bu = newel('button'); bu.style.height = '45px'; bu.style.verticalAlign = 'top';
 bu.innerHTML = NextPuzzleText();
 bu.className = 'nextpuzzlebutton';
 var bustyle = 'this.style.position="relative"; this.style.top = "5px"; this.style.background = "#fff"; ';
 var buact = 'galogg("nextpuzzle",'+(n+1)+'); puzzleset_load('+(n+1)+');';
 buact = 'setTimeout(function(){'+buact+'},0);';
 bu.setAttribute('onclick',bustyle+buact);
 return bu;
}
function nextsetlink()
{
	var a = newel('a'); a.innerHTML = NextSetText();
	var href = puzzleset_next(); a.target = '_top';
	if (!href) a.style.visibility = 'hidden';
	//console.log('nextsetlink() sees href='+href);
	if (href.indexOf('#1')==-1) href += '#1';
	if (href.indexOf('reload')==0) href = '';
	a.href = href;
	a.style.fontSize = 'medium';
	a.addEventListener('click',nextsetlinkclicked);
	a.style.height = '45px'; a.style.verticalAlign = 'top'; a.style.display = 'inline-block';
	if (puzzleset_next() == 'reload')
	{
		a.style.color = 'blue';
	}
	return a;
}

function puzzleset_load(n)
{
 if (zadanie) startfen(zadanie); // to clean up before loading another puzzle
 puzzleset_setcurrentnumber(n);
 load_puzzle(puzzleset[n]);
 //if (puzzleset_next() != 'reload') parent.location.hash = n;
 if (puzzleset_next() != 'reload')
 {
	 var oldurl = parent.location.href;
	 var i = oldurl.indexOf('#');
	 var newurl = ((i > -1) ? oldurl.substring(0,i) : oldurl) + '#' + n;
	 parent.history.replaceState(null, null, newurl);
 }
 for (var i=1; i<=puzzleset_lastnumber(); i++)
 {
  if (el('puzzlestarter'+i))
  {
   el('puzzlestarter'+i).style.background = (i==n) ? 'black' : '#eee';
   el('puzzlestarter'+i).style.color      = (i==n) ? 'white' : 'black';
   el('puzzlestarter'+i).style.fontWeight = (i==n) ? 'bold' : 'normal';
  }
 }
 if (gallery)
 {
  var ile = gallery.length;
  if (ile>0)
  {
   var width = el('gallery').clientWidth;
   if (width >= 334)
   {
	   el('gallery').innerHTML = '<div id=innergallery>' + gallery[(n-1)%ile] + '</div>';
	   el('innergallery').addEventListener('click',      function(){ galogg('gallery-click','-'); } );
   }
  }
 }
}

var g_nextpuzzleset = ''; function puzzleset_next() { return g_nextpuzzleset; }
var g_polishptitle = '', g_englishtitle = '', g_russiantitle = ''; 
function puzzleset_title()
{
	var lang = detectlanguage();
	if (lang == 'pl') return g_polishptitle;
	if (lang == 'ru') return g_russiantitle;
	return g_englishtitle;
}

function init_puzzleset(nextpuzzleset,polishtitle,englishtitle,russiantitle)
{
 g_nextpuzzleset = nextpuzzleset; g_polishptitle = polishtitle; g_englishtitle = englishtitle; g_russiantitle = russiantitle;
 
 function puzzlesetheader()
 {
	 var title = puzzleset_title();
	 var div = newel('div'); div.innerHTML = title;
	 div.style.marginLeft = '2px'; div.style.marginRight = '3px';
	 div.style.display = 'inline-block';
	 div.style.fontSize = '20px';
	 if (ismobile()) div.style.fontSize = '100%';
	 return div;
 }
 function puzzlestarter(n)
 {
  var a = newel('div'); a.id = 'puzzlestarter'+n;
  a.innerHTML = n;
  a.style.display = 'inline-block';
  a.style.textAlign = 'center';
  a.style.cursor = 'pointer';
  a.style.borderRadius = '50%';
  a.style.border = '5px solid rgba(255,255,255,0)';
  a.style.verticalAlign = 'top';
  a.style.margin = '1px';
  a.style.fontFamily = 'mono'; a.style.fontSize = '11px'; a.style.width = '1em';
  if (n == puzzleset_currentnumber()) a.style.color = '#663366';
  a.setAttribute('onclick','galogg("puzzlestarter",'+n+'); puzzleset_load('+n+');');
  return a;
 }
 puzzleset_clean();
 init_puzzlediv();
 el('beforepuzzlediv').appendChild(puzzlesetheader());
 if (puzzleset.length >= 3) for (var i=1; i < puzzleset.length; i++) el('beforepuzzlediv').appendChild(puzzlestarter(i));
 var meta = finishimgdiv(); meta.id = 'komplecik';
 var goodheight = 22;
 meta.style.height =  goodheight + 'px'; meta.style.verticalAlign = 'top';
 meta.style.width = (goodheight * 5/3) + 'px';
 meta.style.opacity = '0.2'; meta.style.cursor = 'not-allowed';
 if (puzzleset.length >= 3) el('beforepuzzlediv').appendChild(meta);
 var next = puzzleset_next();
 if (next)
 {
	  if (next.indexOf('#1') == -1) next += '#1';
	  if (next.indexOf('reload')==0) next = '';
	  var nextlink = newel('a'); nextlink.href = next; nextlink.target = '_top';
	  //nextlink.innerHTML = '&#9658';
	  nextlink.appendChild(nexttriangle());
	  nextlink.style.textDecoration = 'none'; nextlink.style.color = 'black';
	  nextlink.style.fontSize = goodheight + 'px';
	  el('beforepuzzlediv').appendChild(nextlink);
	  nextlink.addEventListener('click',nextsetlinkclicked,false);
 }
 puzzleset_load(puzzleset_currentnumber());
}

function nextlinkclicked()
{
 var ile = 0;
 for (var i=1; i <= puzzleset_lastnumber(); i++) if (el('puzzlestarter'+i).className == 'solved') ile++;
 galogg('nextset-navi',''+ile+' of '+puzzleset_lastnumber());
}
function nextsetlinkclicked()
{
 var ile = 0;
 for (var i=1; i <= puzzleset_lastnumber(); i++) if (el('puzzlestarter'+i).className == 'solved') ile++;
 var procent = ''+(100*ile/puzzleset_lastnumber()).toFixed(0);
 galogg('nextset-final',procent);
}

function puzzleset_marksuccess(n)
{
 galogg('puzzle-success',n);
 if (puzzleset_lastnumber() == 1) return;
 el('puzzlestarter'+n).style.border = "5px solid #aaffaa";
 el('puzzlestarter'+n).className = 'solved';
 var komplecik = true;
 for (var i=1; i <= puzzleset_lastnumber(); i++)
  if (el('puzzlestarter'+i).className != 'solved') komplecik = false;
 if (komplecik)
 {
  el('komplecik').style.opacity = '1'; el('komplecik').style.cursor = 'auto';
  el('beforepuzzlediv').style.background = '#aaffaa';
  galogg('setsuccess',puzzleset_lastnumber());
 }
}

function sanitize_innerHTML(a)
{
 return a.replace(/'/g, "&apos;");
}

function puzzleiframesrcdoc(wizboardpath,puzzleset,puzzle,gallery,nextpuzzleset,polishtitle,englishtitle,russiantitle,variant,randomsymmetry)
{
	puzzle = replace(puzzle,'&amp;','&');
	
	var lang = detectlanguage();
	var pq = new URLSearchParams(puzzle || '');
	if (pq.has('lang')) lang = pq.get('lang');
	
	function isRomantic()
	{
		 var query = new URLSearchParams(window.location.search);
		 if (query.has('variant') && query.get('variant')=='romantic') return true;
		 return (variant == 'romantic');
	}
	
	function isGurupassive()
	{
		let query = new URLSearchParams(window.location.search);
		if (query.has('passive') && query.get('passive')=='guru') return true;
		if (puzzle)
		{
			query = new URLSearchParams(puzzle);
		    return (query.has('passive') && query.get('passive')=='guru');
		}
		if (puzzleset)
		{
			query = new URLSearchParams(puzzleset[1]); // the first puzzle
		    return (query.has('passive') && query.get('passive')=='guru');
		}
		return false;
	}
		
	function htmlheaderdoc()
	{
	 var doc = '<!DOCTYPE html>\n';
	 doc += '<html lang='+lang+'>'; //doc += (isPolish()) ? "<html lang=pl>\n" : "<html lang=en>\n";
	 doc += '<head>\n';
	 doc += '<meta charset="UTF-8">\n';
	 doc += '<meta name="viewport" content="width=device-width, initial-scale=1">\n';
	 doc += '</head>\n';
	 doc += '<body>\n';
	 return doc;
	}

	function cachekiller()
	{
		var a = new Date();
		return ''+a.getMonth()+a.getDate()+a.getHours()+a.getMinutes();
	}
	function puzzledoc()
	{
		var romantic = ''; if (isRomantic()) romantic = '?variant=romantic';
		var doc = '';
		doc += "<sc"+"ript src=\""+wizboardpath+'wizboard-js.php'+romantic+"\"></sc"+"ript>\n";
		doc += '<sc'+'ript>\n';
		var nocache = '?niekaszuj=' + cachekiller();
		doc += 'var garbochesspath = "' + wizboardpath + 'garbochess/garbochess.js'+nocache+'";\n';
		if (isGurupassive())
		{
			doc += 'var garbochesspath = "' + wizboardpath + 'garbochess/garbochess-gurupassive.js.php";\n';
		}
		if (isRomantic()) doc += 'var garbochesspath = "' + wizboardpath + 'garbochess/garbochess-romantic.js.php";\n';
		doc += 'var wizboardpath = "'+wizboardpath+'";\n';
		if (gallery)
		{
			doc += 'var gallery = [\n';
			for (var i=0; i < gallery.length; i++) doc += "'" + sanitize_innerHTML(gallery[i]) + "',\n";
			doc = doc.substr(0,doc.length-2);
			doc += '\n];\n';
		}
		if (puzzleset)
		{
		    if (randomsymmetry) doc += 'var randomsymmetry = true;\n';
			doc += 'var puzzleset = [\n';
			for (var i=0; i < puzzleset.length; i++) doc += "'" + puzzleset[i] + "',\n";
			doc = doc.substr(0,doc.length-2);
			doc += '\n];\n';
			var init = 'init_puzzleset("'+nextpuzzleset+'","'+polishtitle+'","'+englishtitle+'","'+russiantitle+'");\n';
			doc += init;
		}
		else
		{
			doc += 'init_puzzlediv();\n';
			doc += 'load_puzzle("'+puzzle+'");\n';
		}
		doc += '</scr'+'ipt>\n';
		return doc;
	}

 var doc = htmlheaderdoc();
 doc += puzzledoc();
 doc += '</body>\n</html>\n';
 return doc;
}

function srcdociframe(framka,doc)
{
 if ('srcdoc' in framka)
 {
	 framka.setAttribute('srcdoc',doc);
 }
 else
 {
	 framka.contentDocument.write(doc);
	 framka.contentDocument.close();
 }
}

function initpuzzleiframe(framka,wizboardpath,puzzleset,puzzle,gallery,nextpuzzleset,polishtitle,englishtitle,russiantitle,variant,randomsymmetry)
{
 var doc = puzzleiframesrcdoc(wizboardpath,puzzleset,puzzle,gallery,nextpuzzleset,polishtitle,englishtitle,russiantitle,variant,randomsymmetry);
 //framka.style.resize = 'both';
 framka.style.border = 'none';
 srcdociframe(framka,doc);
 framka.contentWindow.focus();
 //if (isFirefox()) framka.setAttribute('allowfullscreen','true');
 //framka.setAttribute('webkitallowfullscreen','true'); 
 //framka.setAttribute('mozallowfullscreen','true');
 framka.setAttribute('allowfullscreen','true'); // needed for Firefox and Safari
 framka.setAttribute('allow','fullscreen');
}
/*
function resizeframka()
{
	var framka = el('framka');
	var height = window.innerHeight;
	var navheight = el('nav').clientHeight;
	framka.style.height = (height - navheight - 8) + 'px';
	framka.style.resize = 'none';
}
function adjust_nav_fontsize()
{
	var a = el('nav');
	if (a.scrollHeight <= a.clientHeight && a.scrollWidth <= a.clientWidth) return;
	var fontsize = parseInt(a.style.fontSize);
	a.style.fontSize = (fontsize-1) + '%';
	setTimeout(adjust_nav_fontsize,1);
}
*/



// wizboard pgn viewer

function load_puzzle_pgnviewer(query)
{
/*	function wariant2indexes(line,nr)
	{
	 resetfen();
	 var ile = line.length;
	 if (ile == 0) return [];
	 var ret = [];
	 for (var i=0; i<ile; i++)
	 {
	  var SANmove = line[i];
	  var index = makeMove_returnIndex(SANmove);
	  if (index>-1)
	  {
	   ret.push(index);
	  }
	  else
	  {
	   //document.getElementById('errorsdiv').style.display = 'inline';
	   //var errors = document.getElementById('errormessages');
	   var errorline = [];
	   for (var j=0; j<ile; j++)
		if (j == i) errorline.push( '<b>'+line[i]+'</b>' );
		else if (j > i) errorline.push('<i>'+line[j]+'</i>');
		else errorline.push( line[j] ); 
	   var blackstarts = fen.indexOf(' b ') > -1;
	   document.body.innerHTML += '<p>'+nr+') '+ponumerujarray(errorline,blackstarts,'')+'</p>';
	   return false;
	  }
	 }
	 return ret;
	}
*/
 pozadaniu = false;
 //initialize_globals_from_query(query);
	{
	 puzzle = 'pgnviewer';
	 query = new URLSearchParams(query);
		  if (query.has('p'))   transferfen = query.get('p');
	 else if (query.has('fen')) transferfen = query.get('fen');
	 else                       transferfen = '0rnbqkbnrXppppppppX8X8X8X8XPPPPPPPPXRNBQKBNR_w_KQkq_-_0_1';
	 matein = '0';
	 encodedlines = 'pgnviewer';
	 mode = '8';
	 Value = '';
	 header = query.get('h');
	 altrules = [];
	 niema = '';
	 successrule = '';
	}
 set_fen_flipcyfra_from_transferfen(transferfen);
 SetControls();
 redrawpuzzlediv(); setTimeout(resizepuzzlediv,1);
 //puzzleindexdocumentready();
	 g_timeout = parseInt(TimePerMove.value); //g_timeout = 1000;
	 UINewGame();
	 document.getElementById('FenTextBox').value = fen;
	 UIChangeFEN();
 var a = query.get('arrlines').split('|');
 var indianty = [];
 for (var i=0; i<a.length; i++)
 {
  if (a[i])
  {
   var sameruchy = tylkoruchy(a[i]);
   if (sameruchy)
   { 
    var line = sameruchy.split('_');
    var wariant = wariant2indexes(line,i+1);
    if (wariant) indianty.push(wariant);
   }
  }
 }
 resetfen();
	 var warianty = indianty;
	 var blackstarts = fen.indexOf(' b ') > -1;
	 var total = totalperceive(warianty,blackstarts);
	 var oklines = total[0];
	 zadanie = new Array();
	 zadanie[0] = fen;
	 zadanie[1] = oklines;
	 zadanie[2] = [];
	 zadanie[3] = matein;
	 zadanie[4] = altrules;
	 zadanie[5] = flipcyfra=='1';
	 zadanie[6] = new Array(); // array of indices of redundant oklines
	 zadanie[7] = total[3]; // SANs
	 zadanie[8] = total[2]; // FENs
	 zadanie[9] = total[1]; // garbomoves
	 startfen(zadanie);
}

// full screen section

function fullscreenbutton(txt)
{
	var bu = (txt) ? newel('button') : newel('span');
	bu.id = 'fullscreenbuttonwithtext';
	txt = txt || '&#9647;';
	bu.innerHTML = txt;
	bu.setAttribute('onclick','toggleFullScreen();');
	bu.style.cursor = 'pointer';
	return bu;
}
function FullScreenIcon(size)
{
	var aa = fullscreenicon(size-4);
	aa.style.margin = '2px';
	aa.setAttribute('onclick','toggleFullScreen();');
	aa.style.cursor = 'pointer';
	aa.style.verticalAlign = 'top';
	return aa;
}
function FullScreenButton()
{
	var bu = newel('button'); bu.innerHTML = '&nbsp;'; bu.style.cursor = 'ne-resize';
	if (isNotFullScreen()) bu.className = 'fullscreenbbbutton';
	fullscreeniconize(bu); bu.setAttribute('onclick','toggleFullScreen();');
	//touchstart_from_click(bu); // this line causes js fullscreen error on Safari
	bu.style.marginRight = '1em';
	bu.style.outline = 'none';
	bu.style.border = 'none';
	bu.style.width = '32px';
	applynoselect(bu);
	return bu;
}

function animasetform(puzzleset)
{
	if (puzzleset == null) return newel('form');
	var fenarray = [];
	for (var i=1; i < puzzleset.length; i++)
	{
		var puzzle = new URLSearchParams(puzzleset[i]);
		var p = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1';
		if (puzzle.has('p'))
		{
			p = puzzle.get('p').split('_').join(' ');
			p = p.split('X').join('/');
			fenarray.push(p.substring(1));
		}
	}
	function param(name,value)
	{
		var i = newel('input');
		i.type = 'hidden';
		i.name = name;
		i.value = value;
		return i;
	}
	var bu = newel('button'); bu.innerHTML = 'download puzzleset animated diagram';
	var form = newel('form');
	form.style.margin = '1em 0 1em 0';
	form.action = 'https://chessdiagram.online/arrowgram.php';
	form.method = 'post';
	form.appendChild(bu);
	form.appendChild(param('download','1'));
	form.appendChild(param('f','')); // no flip
	form.appendChild(param('q','2')); // no arrows, with coordinates
	form.appendChild(param('de','500'));
	form.appendChild(param('a',fenarray2moviediagram(fenarray)));
	return form;
}
function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function detectlanguage()
{
	if (el('langselect')?.selectedIndex > 0) return el('langselect').value;
 var query = new URLSearchParams(location.search);
 if (query.has('lang'))
 {
	 var lang = query.get('lang');
	 setCookie('lang',lang,30);
	 if (lang == 'pl') return 'pl';
	 if (lang == 'ru') return 'ru';
	 if (lang == 'en') return 'en';
 }
 	var pq = new URLSearchParams(puzzle);
	if (pq.has('lang'))
	{
		var lang = pq.get('lang');
		if (lang == 'pl') return 'pl';
		if (lang == 'ru') return 'ru';
		if (lang == 'en') return 'en';		
	}
 if (getCookie('lang')=='pl') return 'pl';
 if (getCookie('lang')=='ru') return 'ru';
 if (getCookie('lang')=='en') return 'en';
 var htmlpl = (document.documentElement.lang.indexOf('pl') == 0);
 var htmlru = (document.documentElement.lang.indexOf('ru') == 0);
 if (htmlpl) return 'pl';
 if (htmlru) return 'ru';
 return 'en';
}

function isPolish() { return detectlanguage() == 'pl'; }
function isRussian() { return detectlanguage() == 'ru'; }
function isEnglish() { return detectlanguage() == 'en'; }

function fliptheboardtext()
{
	if (isRussian()) return "Развернуть доску";
	if (isPolish()) return 'obróć szachownicę';
	return 'flip the board';
}

function clicktoforceenginetomovetext()
{
	if (isRussian()) return 'Заставить компьютер сделать ход';
	if (isPolish()) return 'niech komputer zrobi ruch';
	return 'click to force engine to move';
}

function ruchach(n)
{
	if (isPolish()) return n + ((n==1) ? ' ruchu' : ' ruchach');
	if (isRussian())
	{
		if (n == 1) return '1 ход';
		if (n >= 2 && n <= 4) return n + ' хода';
		return n + ' ходов';
	}
	return n + ' moves';
}

/*
function WhiteWinsInMessage(n)
{
	if (isPolish()) return 'Wygrana białych w '+ruchach(n);
	if (isRussian()) return 'Белые выиграют в '+ruchach(n);
	return 'White wins in '+n+' moves';
}
function WhiteLosesInMessage(n)
{
	if (isPolish()) return 'Przegrana białych w '+ruchach(n);
	if (isRussian()) return 'Белые проиграют в '+ruchach(n);
	return 'White loses in '+n+' moves';
}
function BlackWinsInMessage(n)
{
	if (isPolish()) return 'Wygrana czarnych w '+ruchach(n);
	if (isRussian()) return 'Черные выиграют в '+ruchach(n);
	return 'Black wins in '+n+' moves';
}
function BlackLosesInMessage(n)
{
	if (isPolish()) return 'Przegrana czarnych w '+ruchach(n);
	if (isRussian()) return 'Черные проиграют '+ruchach(n);
	return 'Black loses in '+n+' moves';
}
*/
function WhiteWinsInMessage(n)
{
	if (isPolish()) return '1-0 w '+ruchach(n);
	if (isRussian()) return '1-0 в '+ruchach(n);
	return '1-0 in '+n+' moves';
}
function WhiteLosesInMessage(n)
{
	if (isPolish()) return '0-1 w '+ruchach(n);
	if (isRussian()) return '0-1 в '+ruchach(n);
	return '0-1 in '+n+' moves';
}
function BlackWinsInMessage(n)
{
	if (isPolish()) return '0-1 w '+ruchach(n);
	if (isRussian()) return '0-1 в '+ruchach(n);
	return '0-1 in '+n+' moves';
}
function BlackLosesInMessage(n)
{
	if (isPolish()) return '1-0 w '+ruchach(n);
	if (isRussian()) return '1-0 в '+ruchach(n);
	return '1-0 in '+n+' moves';
}

function WhiteZeroedMessage()
{
 var result = '<fieldset><b>0-1</b></fieldset> ';
 var words = 'Black&nbsp;wins';
 if (isPolish()) words = 'Czarne&nbsp;wygrały.';
 if (isRussian()) words = 'Чёрные&nbsp;выиграли.';
 return result + words;	
}

function BlackZeroedMessage()
{
 var result = '<fieldset><b>1-0</b></fieldset> ';
 var words = 'White&nbsp;wins';
 if (isPolish()) words = 'Białe&nbsp;wygrały.';
 if (isRussian()) words = 'Белые&nbsp;выиграли.';
 return result + words;
}

function BlackWinsMessage()
{
 var result = '<fieldset><b>0-1</b></fieldset> ';
 var words = 'Black&nbsp;wins&nbsp;by&nbsp;checkmate';
 if (isPolish()) words = 'Mat.&nbsp;Czarne&nbsp;wygrały.';
 if (isRussian()) words = 'Мат.&nbsp;Чёрные&nbsp;выиграли.';
 return result + words;
}

function WhiteWinsMessage()
{
 var result = '<fieldset><b>1-0</b></fieldset> ';
 var words = 'White&nbsp;wins&nbsp;by&nbsp;checkmate';
 if (isPolish()) words = 'Mat.&nbsp;Białe&nbsp;wygrały.';
 if (isRussian()) words = 'Мат.&nbsp;Белые&nbsp;выиграли.';
 return result + words;
}

function DrawResult()
{
 return '<fieldset><b>&#189;-&#189;</b></fieldset> ';
}

function NoPowerMessage()
{
 var words = ' draw&nbsp;by&nbsp;insufficient&nbsp;material';
 if (isPolish()) words = ' Remis.&nbsp;Brak&nbsp;siły&nbsp;matującej.';
 if (isRussian()) words = ' Ничья&nbsp;из-за&nbsp;недостаточности&nbsp;материала.';
 return DrawResult() + words;
}

function RepetitionMessage()
{
 var words = ' draw&nbsp;by&nbsp;repetition';
 if (isPolish()) words = ' Remis.&nbsp;Trzy&nbsp;razy&nbsp;ta&nbsp;sama&nbsp;pozycja.';
 if (isRussian()) words = ' Ничья из-за троекратного повторения позиции.';
 return DrawResult() + words;
}

function StaleMateMessage()
{
 var words = ' draw&nbsp;by&nbsp;stalemate';
 if (isPolish()) words = ' Pat.&nbsp;Remis.';
 if (isRussian()) words = ' Пат.&nbsp;Ничья.';
 return DrawResult() + words;
}

function FiftyMessage()
{
 var words = ' draw&nbsp;by&nbsp;fifty&nbsp;moves';
 if (isPolish()) words = ' Remis.&nbsp;Reguła&nbsp;50&nbsp;posunięć.';
 if (isRussian()) words = ' Ничья&nbsp;по&nbsp;правилу&nbsp;50&nbsp;ходов.';
 return DrawResult() + words;
}

function ResignText()
{
 var words = 'RESIGN';
 if (isPolish()) words = 'od nowa';
 if (isRussian()) return 'Начать заново';
 return words;
}

function TakeBackText()
{
 if (isRussian()) return 'Отменить ход';
 if (isPolish()) return 'cofnij';
 return 'take back';
}

function NEXTbuttonText()
{
 if (isRussian()) return 'Дальше';
 if (isPolish()) return 'kolejny wariant&hellip;';// 'DALEJ';
 return 'next line&hellip;'; //return 'NEXT';
}

function TAKEBACKbuttonText()
{
 if (isRussian()) return 'Отменить ход';
 if (isPolish()) return 'COFNIJ';
 return 'TAKE BACK';
}

/*function LimitReachedText()
{
 if (isRussian()) return 'Количество ходов исчерпано.<br><br><br>Попробуйте сначала.';
 if (isPolish()) return "Wyczerpano liczbę ruchów.<br><br>Spróbuj od nowa.";
 return "Move limit reached.<br><br>Try again from the start.";
}*/
function LimitReachedText()
{
 if (isRussian()) return 'Количество ходов<br>исчерпано.';
 if (isPolish()) return "Wyczerpano<br>liczbę ruchów.";
 return "Move limit<br>reached.";
}

function QueenText()
{
 if (isPolish()) return 'Hetman';
 return 'Queen';
}

function RookText()
{
 if (isPolish()) return 'Wieża';
 return 'Rook';
}

function KnightText()
{
 if (isPolish()) return 'Skoczek';
 return 'Knight';
}

function AnimateSolutionText()
{
 if (isRussian()) return 'Показать';// решение';
 if (isPolish()) return 'pokaż'; //'Animacja rozwiązania';
 return 'show me';//'animate solution';
}

function NextPuzzleText()
{
 if (isRussian()) return 'Следующая<br>задача';
 if (isPolish()) return 'Następne<br>zadanie';
 return 'NEXT<br>PUZZLE';
}

function NextSetText()
{
 if (isRussian()) return 'Следующий набор';// задач';
 if (isPolish()) return 'Następny zestaw';
 return 'NEXT SET';
}

function restartpuzzletext()
{
	if (isRussian()) return 'Oбновить интерфейс задачи';
	if (isPolish()) return 'restart zadania';
	return 'restart puzzle';
}
function stoppuzzletext()
{
	if (isRussian()) return 'Oстановить задачу (для анализа)';
	if (isPolish()) return 'przerwa na analizę';
	return 'stop puzzle (to analyze)';
}
function togglefullscreentext()
{
	if (isRussian()) return 'Переключить полноэкранный режим';
	if (isPolish()) return 'przełącz tryb pełnoekranowy';
	return 'toggle full screen';
}
function showsolutiontext()
{
	if (isRussian()) return 'Раскрыть решение';
	if (isPolish()) return 'pokaż rozwiązanie';
	return 'show solution';
}
function wrongmovealerttext()
{
	if (isRussian()) return 'Объявлять о неверном ходу';
	if (isPolish()) return 'ogłaszaj błędny ruch';
	return 'wrong move alert';
}
function requireallalternativesolutionstext()
{
	if (isRussian()) return 'Требуются все альтернативные решения';
	if (isPolish()) return 'wymagaj wszystkie alternatywne rozwiązania';
	return 'require all alternative solutions';
}
function userplaysbothsidestext()
{
	if (isRussian()) return 'Пользователь играет за обе стороны';
	if (isPolish()) return 'użytkownik gra za obie strony';
	return 'user plays both sides';
}
function playblindfoldonanemptyboardtext()
{
	if (isRussian()) return 'Игра вслепую на пустой доске';
	if (isPolish()) return 'gra na ślepo na pustej szachownicy';
	return 'play blindfold on an empty board';
}
function thecomputermovesonlywhencheckedorthreatenedwithmatetext()
{
	if (isRussian()) return 'Kомпьютер играет, только если ему шах или угрожает мат';
	if (isPolish()) return 'komputer robi ruch tylko gdy jest szach lub grozi mat';
	return 'the computer moves only when checked or threatened with mate';
}
function enginerepliestousermovesinpostmortemanalysistext()
{
	if (isRussian()) return 'Компьютер отвечает пользователю во время анализа';
	if (isPolish()) return 'silnik odpowiada użytkownikowi w analizie';
	return 'engine replies to user moves in postmortem analysis';
}
function forceenginetomovetext()
{
	if (isRussian()) return 'Заставить компьютер сделать ход';
	if (isPolish()) return 'wymuś ruch silnikiem';
	return 'force engine to move';
}
function orpressspacebarorclickthetriangletext()
{
	if (isRussian()) return 'или нажмите пробел или треугольник';
	if (isPolish()) return 'lub naciśnij spację lub kliknij trójkąt';
	return 'or press spacebar or click the triangle';
}
function millisecondspercomputermovetext()
{
	if (isRussian()) return 'Сколько миллисекунд на ход компьютера';
	if (isPolish()) return 'ile milisekund na ruch komputera';
	return 'milliseconds per computer move';
}
function editpuzzleinnewwindowtext()
{
	if (isRussian()) return 'Редактировать в новом окне';
	if (isPolish()) return 'edytuj zadanie w nowym oknie';
	return 'edit puzzle in new window';
}
function analyzeinnewwindowtext()
{
	if (isRussian()) return 'Анализировать в новом окне';
	if (isPolish()) return 'analizuj w nowym oknie';
	return 'analyze in new window';
}
function playtext()
{
	if (isRussian()) return 'Играть';
	if (isPolish()) return 'graj';
	return 'play';
}
function cwicztext()
{
	if (isRussian()) return 'Упр.';
	if (isPolish()) return 'ćwicz';
	return 'practice';
}
function playagainstcomputertext()
{
	if (isRussian()) return 'Играйте против компьютера';
	if (isPolish()) return 'Graj z komputerem';
	return 'Play against computer';
}
function cwiczbezkompatext()
{
	if (isRussian()) return 'Упражняйтесь в одиночку';
	if (isPolish()) return 'Poćwicz sam';
	return 'Practice alone';
}
function CloseAnimationText()
{
	if (isRussian()) return 'Закрыть';
	if (isPolish()) return 'zamknij'; //'zamknij animację';
	return 'close'; //'close animation';
}
function CloseText()
{
	if (isRussian()) return 'Закрыть';
	if (isPolish()) return 'zamknij'; //'zamknij animację';
	return 'close'; //'close animation';
}
function GameOverText()
{
	if (isRussian()) return 'ИГРА ОКОНЧЕНА';
	if (isPolish()) return 'KONIEC GRY';
	return 'GAME OVER';
}
function WhiteToMoveText()
{
	if (isRussian()) return 'ход белых';
	if (isPolish()) return 'ruch białych';
	return 'White to move';
}
function BlackToMoveText()
{
	if (isRussian()) return 'ход черных';
	if (isPolish()) return 'ruch czarnych';
	return 'Black to move';
}
function BlockedChessmanText()
{
	if (isRussian()) return 'некуда ходить'; //'Этой фигуре некуда ходить';
	if (isPolish()) return 'zablokowana bierka'; //'Zablokowana bierka nie ma ruchu';
	return "blocked"; //"Blocked Chessman Can't Move";
}
function EngineThinkingText()
{
	if (isRussian()) return 'компьютер думает';
	if (isPolish()) return 'komputer myśli';
	return "computer thinking";
}

function emptypuzzlesetnotice()
{
 if (isPolish()) return 'Nie mamy takich zadań w naszej bazie.';
 return 'We do not have such puzzles in our database.';
}

function passivevariantsubheadertext()
{
	if (isRussian()) return 'Kомпьютер играет, только если ему шах или угрожает мат в 1.';
	if (isPolish()) return 'Komputer robi ruch, tylko gdy jest szach lub grozi mat w 1.';
	return 'The computer moves only when checked or threatened with mate in 1.';
}
function gurupassivesubheadertext(eval)
{
 if (isPolish()) return 'Komputer robi ruch, tylko gdy jego eval jest poniżej ' + eval + '.';
 return 'The computer moves only when its eval is below ' + eval + '.';
}


function gotoexamtext()
{
	if (isPolish()) return 'egzamin';
	return 'go to exam';
}

function forcemovealerttext()
{
	if (isPolish()) return 'Czy na pewno chcesz, aby komputer wykonał za ciebie ruch?';
	return 'Are you sure that you want the computer to make a move for you?';
}

function selfplayexamtiptext()
{
	//'Najpierw przeanalizuj bez pomocy komputera. Graj obiema stronami. Potem naciśnij [egzamin], żeby sprawdzić swoje rozwiązanie.';
	//if (isPolish()) return "Graj obiema stronami bez pomocy komputera. Kliknij ["+gotoexamtext()+"], aby sprawdzić swoje rozwiązanie.";
	if (isPolish()) return "Po swojej analizie kliknij ["+gotoexamtext()+"].";
	return "Play both sides without computer's aid. Click ["+gotoexamtext()+"] to check your solution.";
}
function svgelement(tag)
{
 var a = document.createElementNS("http://www.w3.org/2000/svg",tag);
 if (tag == 'svg') a.setAttributeNS("http://www.w3.org/2000/xmlns/", 'xmlns', "http://www.w3.org/2000/svg");
 return a;
}

function squareIndex(x,y,width,height,flip)
{
 var sqi = (y-1)*width + x;
 if (flip) return width*height+1-sqi;
 return sqi; 
}

function squarecolor(x,y,width,height,flip)
{
 //x = parseInt(x); y = parseInt(y); width = parseInt(width); height = parseInt(height); flip = (flip==true);
 var a1color = ((width%2 + height%2)%2 == 1 && flip) ? 'light' : 'dark';
 if ((x+y)%2 == 0) return a1color;
 if (a1color == 'dark') return 'light';
 return 'dark';
}

function bierka(what,style,white1,white2,black1,black2)
{
 white1 = white1 || "#fff"; white2 = white2 || "#000";
 black1 = black1 || "#000"; black2 = black2 || "#fff";
 function alterblackpiece(svg)
 {
  svg = svg.replace(/fill="#000"/g,'fill="'+black1+'"');
  svg = svg.replace(/fill="#fff"/g,'fill="'+black2+'"');
  return svg;
 }
 function alterwhitepiece(svg)
 {
  svg = svg.replace(/fill="#fff"/g,'fill="'+white1+'"');
  svg = svg.replace(/fill="#000"/g,'fill="'+white2+'"');
  return svg;
 }
 var a = svgmerida(what); if (style == 'alpha') a = svgalpha(what); else if (style == 'linares') a = svglinares(what);
 var div = document.createElement('div'); div.appendChild(a); var svg = div.innerHTML;
 var isWhite = (what=='K'||what=='Q'||what=='R'||what=='B'||what=='N'||what=='P');
 var div = document.createElement('div');
 div.innerHTML = (isWhite) ? alterwhitepiece(svg) : alterblackpiece(svg);
 return div.firstChild;
}

function bareboard(poza,width,height,flip,size,style,darkR,darkG,darkB,lightR,lightG,lightB,white1,white2,black1,black2)
{
 width = width || 8; height = height || 8; size = size || 37; style = style || 'merida';
 width = parseInt(width); height = parseInt(height); size = parseInt(size);
 var dark = 'rgb(187,187,187)'; var light = 'rgb(238,238,238)';
 function rgb(r,g,b) { return 'rgb('+Math.round(r)+','+Math.round(g)+','+Math.round(b)+')'; }
 dark = rgb(darkR,darkG,darkB); light = rgb(lightR,lightG,lightB);
 
 var svg = svgelement('svg'); svg.setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:xlink", "http://www.w3.org/1999/xlink");
 svg.setAttribute('width', size*width); svg.setAttribute('height', size*height);
 svg.setAttribute('viewBox', '0 0 '+size*width+' '+size*height);

 //svg.appendChild(lightblondepattern());
 //light = 'url(#lightblonde)';
 //svg.appendChild(darkblondepattern());
 //dark = 'url(#darkblonde)';
 //svg.appendChild(randomgradient('asas',187,187,187));
 //dark = 'url(#asas)';

 var tlo = svgelement('rect'); svg.appendChild(tlo);
 tlo.setAttribute('width', size*width); tlo.setAttribute('height', size*height);
 tlo.setAttribute('fill',dark); 
 var squares = svgelement('g'); squares.setAttribute('class','squares'); svg.appendChild(squares);
 //squares.setAttribute('fill',light);
 var figurines = svgelement('g'); svg.appendChild(figurines);
 for (var x=1; x<=width; x++) for (var y=1; y<=height; y++)
 {
  if (squarecolor(x,y,width,height,flip)=='light')
  {
   var r = svgelement('rect'); squares.appendChild(r);
   r.setAttribute('width',size); r.setAttribute('height',size);
   r.setAttribute('x',(x-1)*size); r.setAttribute('y',(height-y)*size);
   r.setAttribute('fill',light);
   //var fillid = 'f'+x+y;
   //squares.appendChild(randomgradient(fillid,lightR,lightG,lightB));
   //r.setAttribute('fill','url(#'+fillid+')');
  }

  if (squarecolor(x,y,width,height,flip)=='dark')
  {
   var r = svgelement('rect'); squares.appendChild(r);
   r.setAttribute('width',size); r.setAttribute('height',size);
   r.setAttribute('x',(x-1)*size); r.setAttribute('y',(height-y)*size);
   r.setAttribute('fill',dark);
   //var fillid = 'f'+x+y;
   //squares.appendChild(randomgradient(fillid,darkR,darkG,darkB));
   //r.setAttribute('fill','url(#'+fillid+')');
  }
 
  var what = poza[squareIndex(x,y,width,height,flip)];
  if (what != '_')
  {
   var a = bierka(what,style,white1,white2,black1,black2);
   figurines.appendChild(a);
   a.setAttribute('width',size); a.setAttribute('height',size);
   a.setAttribute('x',(x-1)*size); a.setAttribute('y',(height-y)*size);
  }
 }
 return svg;
}

function rimrect4(x,y,size,R,G,B,tR,tG,tB)
{
 var gr = svgelement('g');
 for (var i=0; i<=4; i++)
 {
  var re = svgelement('rect'); gr.appendChild(re);
  re.setAttribute('x',x+i); re.setAttribute('y',y+i);
  re.setAttribute('width',size-2*i); re.setAttribute('height',size-2*i);
  function rgb(r,g,b) { return 'rgb('+Math.round(r)+','+Math.round(g)+','+Math.round(b)+')'; }
  var r = (R-tR)/4, g = (G-tG)/4, b = (B-tB)/4;
  re.setAttribute('fill',rgb(R-i*r,G-i*g,B-i*b));
 }
 return gr;
}

function rimsquares(kwadraciki, width,height,flip,size,darkR,darkG,darkB,lightR,lightG,lightB)
{
 var g = svgelement('g'); if (kwadraciki.length == 0) return g;
 var kwadraciki = kwadraciki.split('A');
 for (var i=0, ile=kwadraciki.length; i<ile; i++)
 {
  var ar = kwadraciki[i].split('Q');
  var x = parseInt(ar[0]), y = parseInt(ar[1]);
  var tR,tG,tB;
  var islight = (squarecolor(x,y,width,height,false)=='light');
  if (!islight) { tR = darkR; tG = darkG; tB = darkB; }
           else { tR = lightR; tG = lightG; tB = lightB; }
  x = (flip) ? width-x+1 : x;
  y = (flip) ? height-y+1 : y;
  var r = rimrect4((x-1)*size,(height-y)*size,size,ar[2],ar[3],ar[4],tR,tG,tB);
  g.appendChild(r);
 }
 return g;
}

function rimsboard(poza,width,height,flip,size,style,darkR,darkG,darkB,lightR,lightG,lightB,kwadraciki)
{
 //var dark = 'rgb('+darkR+','+darkG+','+darkB+')';
 //var light = 'rgb('+lightR+','+lightG+','+lightB+')';
 var b = bareboard(poza,width,height,flip,size,style,darkR,darkG,darkB,lightR,lightG,lightB);
 var r = rimsquares(kwadraciki,width,height,flip,size,darkR,darkG,darkB,lightR,lightG,lightB);
 var s = b.getElementsByClassName('squares')[0];
 s.appendChild(r);
 return b;
}

function arrow(x1,y1,x2,y2,color)
{
 var h = 10; // arrow head height -- 13
 var a = 5; // 2a arrow base length -- 7
 var b = 1; // 2b arrow line width -- 1
 var z = Math.pow( 0.0+Math.pow((y2-y1),2)+Math.pow((x1-x2),2) , -0.5);
 var Kx = x2 - h*z*(x2-x1); var Ky = y2 - h*z*(y2-y1);
 var KAx = Kx + (b)*z*(y2-y1); var KAy = Ky + (b)*z*(x1-x2);
 var KBx = Kx - (b)*z*(y2-y1); var KBy = Ky - (b)*z*(x1-x2);
 var Cx = Kx + (a)*z*(y2-y1); var Cy = Ky + (a)*z*(x1-x2);
 var Dx = Kx - (a)*z*(y2-y1); var Dy = Ky - (a)*z*(x1-x2);
 var Ax = x1 + b*z*(y2-y1); var Ay = y1 + b*z*(x1-x2);
 var Bx = x1 - b*z*(y2-y1); var By = y1 - b*z*(x1-x2);
 var points = ''+Ax+','+Ay+' '+KAx+','+KAy+' '+Cx+','+Cy+' '+x2+','+y2+' '+Dx+','+Dy+' '+KBx+','+KBy+' '+Bx+','+By;
 var a = svgelement('polygon');
 a.setAttribute('points',points); a.setAttribute('fill',color); //a.setAttribute('stroke-width',0);
 return a;
}

function allarrows(arrows, width,height,flip,size)
{
 var g = svgelement('g'); if (arrows.length == 0) return g;
 var arrows = arrows.split('A');
 for (i=0, ile=arrows.length; i<ile; i++)
 {
  var ar = arrows[i].split('Q');
  var x1 = parseInt(ar[0]), y1 = parseInt(ar[1]), x2 = parseInt(ar[2]), y2 = parseInt(ar[3]);
  var R = ar[4], G = ar[5], B = ar[6];
  var color = 'rgb('+R+','+G+','+B+')';
  if (flip) { x1 = width-x1+1; y1 = height-y1+1; x2 = width-x2+1; y2 = height-y2+1; }
  x1 = (x1-1)*size + Math.floor(size/2); y1 = (height-y1)*size + Math.floor(size/2);
  x2 = (x2-1)*size + Math.floor(size/2); y2 = (height-y2)*size + Math.floor(size/2);
  g.appendChild( arrow(x1,y1,x2,y2,color) );
 }
 return g;
}

function arrowsrimsboard(poza,width,height,flip,size,style,darkR,darkG,darkB,lightR,lightG,lightB,kwadraciki,arrows)
{
 var r = rimsboard(poza,width,height,flip,size,style,darkR,darkG,darkB,lightR,lightG,lightB,kwadraciki);
 r.appendChild(allarrows(arrows, width,height,flip,size));
 return r;
}

function flipliterka(i,width,flip)
{
 var letters = ['','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
 if (flip) i = width-i+1; if (i<=26) return letters[i]; else return i;
}
function flipcyferka(i,height,flip) { if (flip) return i; else return height+1-i; }

function coordinates(width,height,flip,size,rim,fontcolor)
{
 var fontsize = size*0.3;
 var fontfamily = '"Lucida Console", Monaco, monospace'; fontfamily = 'Verdana';
 var g = svgelement('g');
 g.setAttribute('font-size',fontsize); g.setAttribute('fill',fontcolor);
 g.setAttribute('font-family',fontfamily); g.setAttribute('font-weight','bold');
 g.setAttribute('pointer-events','none');
 for (var x=1; x<=width; x++)
 {
  var xx = (x-1)*size+rim+size*0.5-fontsize*0.3;
  var t = svgelement('text'); g.appendChild(t);
  t.setAttribute('x',xx);
  t.setAttribute('y',rim-fontsize*0.3);
  t.innerHTML = flipliterka(x,width,flip);
  var t = svgelement('text'); g.appendChild(t);
  t.setAttribute('x',xx);
  t.setAttribute('y',size*height+rim+fontsize);
  t.innerHTML = flipliterka(x,width,flip);
 }
 for (var y=1; y<=height; y++)
 {
  var yy = (y-1)*size+rim+size*0.5+fontsize*0.36;
  var t = svgelement('text'); g.appendChild(t);
  t.setAttribute('y',yy); t.setAttribute('x',rim-fontsize);
  t.innerHTML = flipcyferka(y,height,flip);
  var t = svgelement('text'); g.appendChild(t);
  t.setAttribute('y',yy); t.setAttribute('x',rim+size*width+fontsize*0.2);
  t.innerHTML = flipcyferka(y,height,flip);	 
 }
 return g;
}

function tomovesymbol(width,height,flip,size,rim,whitetomove)
{
 var points, p = svgelement('polygon'); p.setAttribute('stroke-width',1); p.setAttribute('stroke','#000');
 var right = 2*rim+width*size-1, left = right - rim,  middle = (left+right)/2;
 var lastx = width*size + 2*rim - 1, lasty = height*size + 2*rim - 1;
 var toppoints = ''+left+',1 '+right+',1 '+middle+','+(rim+1) ;
 var botpoints = ''+left+','+(lasty)+' '+right+','+(lasty)+' '+middle+','+(lasty-rim);
 if (whitetomove) points = (flip) ? toppoints : botpoints; else points = (flip) ? botpoints : toppoints;
 var fill = (whitetomove) ? '#ffffff' : '#000';
 p.setAttribute('fill',fill); p.setAttribute('points',points);
 return p;
}

function rimgram(poza,width,height,flip,size,style,darkR,darkG,darkB,lightR,lightG,lightB,kwadraciki,arrows,rimfill,borderfill,fontcolor,whitetomove,blacktomove)
{
 var border=1, rim = parseInt(size*0.6); rim = parseInt(size*0.5);
 width = parseInt(width); height = parseInt(height);
 var svg = svgelement('svg');
 svg.setAttribute('width', size*width+2*rim); svg.setAttribute('height', size*height+2*rim);
 svg.setAttribute('viewBox', '0 0 '+(size*width+2*rim)+' '+(size*height+2*rim));
 var r = svgelement('rect'); svg.appendChild(r);
 r.setAttribute('width', size*width+2*rim); r.setAttribute('height', size*height+2*rim);
 r.setAttribute('fill',rimfill); 
 var r = svgelement('rect'); svg.appendChild(r);
 r.setAttribute('width', size*width+2*border); r.setAttribute('height', size*height+2*border);
 r.setAttribute('fill',borderfill);
 r.setAttribute('x',rim-border); r.setAttribute('y',rim-border);
 var rimlessboard = arrowsrimsboard(poza,width,height,flip,size,style,darkR,darkG,darkB,lightR,lightG,lightB,kwadraciki,arrows);
 rimlessboard.setAttribute('x',rim); rimlessboard.setAttribute('y',rim);
 svg.appendChild(rimlessboard);
 var coord = coordinates(width,height,flip,size,rim,fontcolor);
 svg.appendChild(coord);
 if (whitetomove) svg.appendChild(tomovesymbol(width,height,flip,size,rim,true));
 if (blacktomove) svg.appendChild(tomovesymbol(width,height,flip,size,rim,false));
 return svg;
}

function createsvg(width,height)
{
 var svg = svgelement('svg');
 svg.setAttribute('width',width); svg.setAttribute('height',height);
 svg.setAttribute('viewBox', '0 0 '+width+' '+height);
 return svg;
}

function textgram(gram,toptxt,topsize,topcolor,bottxt,botsize,botcolor,tlo)
{
 topsize = parseInt(topsize); botsize = parseInt(botsize);
 if (isNaN(topsize)) topsize = 0; if (isNaN(botsize)) botsize = 0;
 if (!toptxt) topsize = 0; if (!bottxt) botsize = 0;
 var fontfamily = 'Arial';
 var margin = 5;
 var margintop = ((topsize) ? margin : 0);
 var margins =  margintop + ((botsize) ? margin : 0);
 var width = gram.getAttribute('width');
 var height = parseInt(gram.getAttribute('height')) + topsize + margins + botsize;
 var svg = createsvg(width,height);
 var r = svgelement('rect'); svg.appendChild(r);
 r.setAttribute('width',width); r.setAttribute('height',height); r.setAttribute('fill',tlo);
 svg.appendChild(gram); gram.setAttribute('x',0); gram.setAttribute('y',topsize+margintop);
 if (toptxt)
 {
  var t = svgelement('text'); svg.appendChild(t);
  t.style.whiteSpace = 'pre';
  t.setAttribute('x',0); t.setAttribute('y',topsize);
  t.setAttribute('font-size',topsize); t.setAttribute('fill',topcolor);
  t.setAttribute('font-family',fontfamily); t.innerHTML = toptxt;
 }
 if (bottxt)
 {
  var t = svgelement('text'); svg.appendChild(t);
  t.style.whiteSpace = 'pre';
  t.setAttribute('x',0); t.setAttribute('y',height-1-botsize*0.3);
  t.setAttribute('font-size',botsize); t.setAttribute('fill',botcolor);
  t.setAttribute('font-family',fontfamily); t.innerHTML = bottxt;
 }
 return svg;
}

function textlineform(id,bottom)
{
 function swiez()
 { txt.style.height = fs.value+'px'; txt.style.fontSize = fs.value+'px';
   txt.style.color = ink.value;
   refresh();
 }
 var frm = document.createElement('form'); frm.id = id; frm.style.width = '100%'; 
 var ink = document.createElement('input'); ink.name = 'ink'; ink.type = 'color';
 var fs = document.createElement('input'); fs.name = 'fs'; fs.type = 'number'; fs.value = '20';
 fs.style.width = '3em';
 var txt = document.createElement('input'); txt.name = 'txt';
 txt.style.height = fs.value+'px'; txt.style.fontSize = fs.value+'px'; txt.style.width = '100%';
 txt.style.fontFamily = 'Arial';
 txt.oninput = refresh; ink.oninput = swiez; fs.oninput = swiez;
 if (bottom)
 { frm.appendChild(txt); frm.appendChild(document.createElement('br'));
  frm.appendChild(ink); frm.appendChild(fs); frm.appendChild(document.createTextNode(' text below diagram'));
 } else
 { frm.appendChild(ink); frm.appendChild(fs); frm.appendChild(document.createTextNode(' text above diagram'));
  frm.appendChild(document.createElement('br')); frm.appendChild(txt);
 }
 return frm;
}

function lightblondepattern()
{
 var p = svgelement('pattern');
 p.id = 'lightblonde';
 p.setAttribute('width','1');
 p.setAttribute('height','1');
 p.setAttribute('viewBox','0 0 50 50');
 p.setAttribute('preserveAspectRatio','xMidYMid slice');
 var img = svgelement('image'); p.appendChild(img);
 img.setAttribute('width','50'); img.setAttribute('height', '50');
 var xlinkns="http://www.w3.org/1999/xlink";
 img.setAttributeNS(xlinkns,'href','https://szachydzieciom.pl/diagram/pattern-images/light.jpg'); 
 return p;
}

function darkblondepattern()
{
 var p = svgelement('pattern');
 p.id = 'darkblonde';
 p.setAttribute('width','1');
 p.setAttribute('height','1');
 p.setAttribute('viewBox','0 0 117 117');
 p.setAttribute('preserveAspectRatio','xMidYMid slice');
 var img = svgelement('image'); p.appendChild(img);
 img.setAttribute('width','117'); img.setAttribute('height', '117');
 var xlinkns="http://www.w3.org/1999/xlink";
 img.setAttributeNS(xlinkns,'href','https://szachydzieciom.pl/diagram/pattern-images/dark.jpg'); 
 return p;
}

function randomizecolor(r,g,b)
{
 var radius = 7;
 r = r + Math.random() * 2*radius - radius;
 g = g + Math.random() * 2*radius - radius;
 b = b + Math.random() * 2*radius - radius;
 return 'rgb('+Math.round(r)+','+Math.round(g)+','+Math.round(b)+')';
}

function randomgradient(id,r,g,b)
{
 function rgb(r,g,b) { return 'rgb('+Math.round(r)+','+Math.round(g)+','+Math.round(b)+')'; }
 var gr = svgelement('radialGradient');
 gr.id = id;
 gr.setAttribute('spreadMethod','repeat');
 gr.setAttribute('r','3');//''+(50+Math.floor(50*Math.random()))+'%');
 gr.setAttribute('cx',''+Math.floor(100*Math.random())+'%');
 gr.setAttribute('cy',''+Math.floor(100*Math.random())+'%');
 var st = svgelement('stop'); gr.appendChild(st);
 st.setAttribute('stop-color',rgb(r,g,b));
 st.setAttribute('offset','0%');
 //var st = svgelement('stop'); gr.appendChild(st);
 //st.setAttribute('stop-color',randomizecolor(r,g,b));
 //st.setAttribute('offset','50%');
 var st = svgelement('stop'); gr.appendChild(st);
 st.setAttribute('stop-color',randomizecolor(r,g,b));
 st.setAttribute('offset','100%');

 return gr;
}


function settingsicon(size,black,white)
{
 size = size||16; white = white||'white'; black = black||'black';
 function svgelement(tag) { return document.createElementNS("http://www.w3.org/2000/svg",tag); }
 var svg = svgelement('svg');
 svg.setAttribute('width', size); svg.setAttribute('height', size);
 svg.setAttribute('viewBox', '-50 -50 101 101');
 var c = svgelement('circle'); svg.appendChild(c); 
 c.setAttribute('r',50); c.setAttribute('fill',black);
 var c = svgelement('circle'); svg.appendChild(c);
 c.setAttribute('r',25); c.setAttribute('fill',white);
 for (var n=0; n<8; n++)
 {
  var x = n*Math.PI/4;
  var c = svgelement('circle'); svg.appendChild(c);
  c.setAttribute('cx',(50*Math.cos(x)).toFixed(5)); c.setAttribute('cy',(50*Math.sin(x)).toFixed(5));
  c.setAttribute('r',10); c.setAttribute('fill',white);
 }
 return svg;
}
/*
function projectorgram(svg,url)
{
 var clickdiagram = function()
 {
  var projector = window.open();
  var obrazek = svg.cloneNode(true);
  obrazek.setAttribute('width','90%');
  obrazek.setAttribute('height','90%');
  obrazek.style.cursor = (url) ? 'pointer' : 'auto';
  if (url) obrazek.setAttribute('onclick','location.href="'+url+'"');
  var div = document.createElement('div');
  div.appendChild(obrazek);
  if (url) projector.document.write('<div style="float:left;"><a href="'+url+'">graj z kompem</a></div>');
  projector.document.write(div.innerHTML);
  projector.document.title = 'DIAGRAM';
 };
 svg.addEventListener('click',clickdiagram);
 svg.style.cursor = 'pointer';
 return svg;
}
*/


function pozafromFEN_svgram(fen,width,height)
{
 if (!fen) return "";
 var fenek = fen.split(' ');
 var horizontals = fenek[0].split('/');
 if (horizontals.length != height) return "";
 var pozycja = "";
 for (var y=height-1; y>=0; y--)
 {
  var hora = horizontals[y];
  while (hora != "")
  {
   switch(hora.charAt(0))
   {
    case 'K' : pozycja += "K"; break;
    case 'k' : pozycja += "k"; break;
    case 'Q' : pozycja += 'Q'; break;
    case 'q' : pozycja += 'q'; break;
    case 'R' : pozycja += 'R'; break;
    case 'r' : pozycja += 'r'; break;
    case 'B' : pozycja += 'B'; break;
    case 'b' : pozycja += 'b'; break;
    case 'N' : pozycja += 'N'; break;
    case 'n' : pozycja += 'n'; break;
    case 'P' : pozycja += 'P'; break;
    case 'p' : pozycja += 'p'; break;
    case 'T' : pozycja += 'T'; break;
    case 't' : pozycja += 't'; break;
    case '1' : pozycja += '_'; break;
    case '2' : pozycja += "__"; break;
    case '3' : pozycja += '___'; break;
    case '4' : pozycja += '____'; break;
    case '5' : pozycja += '_____'; break;
    case '6' : pozycja += '______'; break;
    case '7' : pozycja += '_______'; break;
    case '8' : pozycja += '________'; break;
    default  : return ""; 
   }
   hora = hora.substring(1,hora.length);
  }
 }
 if (pozycja.length != width*height) return "";
 return '_' + pozycja;
}

function killscreen(el)
{
 //document.body.style.height = 'initial';
 //document.body.style.overflow = 'initial';	
 document.body.removeChild(el);
}
function clickgraj(wizboardpath,fen,flipcyfra,bezkompa)
{
 var randomname = 'a' + Math.floor( 9090912 + Math.random(9090912) );	
 var screen = newel('div'); screen.id = 'screen'+randomname;
 screen.style.position = 'fixed'; screen.style.zIndex = '777777';
 screen.style.left = '0'; screen.style.width = '100%';
 screen.style.top = '0'; screen.style.height = window.innerHeight+'px';
 screen.style.background = 'white';
 var menu = newel('div');
 menu.style.background = '#dddddd';
 menu.innerHTML = (bezkompa) ? cwiczbezkompatext() : playagainstcomputertext();
 var menuheight = 32; menu.style.height = menuheight + 'px';
 var zamknij = newel('button'); zamknij.innerHTML = '&#10060;'; zamknij.style.float = 'right';
 zamknij.style.height = menuheight+'px';
 zamknij.setAttribute('onclick','killscreen(el("screen'+randomname+'"));');
 menu.appendChild(zamknij);
 screen.appendChild(menu);
 var framka = newel('iframe');
 framka.id = randomname; framka.setAttribute('name',randomname);
 framka.style.position = 'fixed';
 framka.style.left = '0'; framka.style.top = menuheight+'px';
 framka.style.width = '100%';
 framka.style.height = (window.innerHeight - menuheight)+'px';
 var puzzle = '?p=' + flipcyfra + replace(fen,' ','_') + ((bezkompa) ? '&m=64' : '');
 var doc = puzzleiframesrcdoc(wizboardpath,null,puzzle,null);
 screen.appendChild(framka);
 document.body.appendChild(screen);
 srcdociframe(framka,doc); //framka.setAttribute('srcdoc',doc);
 //document.body.style.height = window.innerHeight + 'px'; document.body.style.overflow = 'hidden';
}

function projectorgram(svg,url,fen,flipcyfra)
{
 var clickdiagram = function()
 {
	 gawyklad('projector','svgram');
  var projector = newel('div'); document.body.appendChild(projector);
  projector.style.position = 'fixed'; projector.style.zIndex = '777777';
  projector.style.left = '0'; projector.style.width = '100%';
  projector.style.top = '0'; projector.style.height = window.innerHeight + 'px';
  projector.style.background = 'white';
  projector.style.cursor = 'not-allowed';
  projector.style.textAlign = 'center';
  projector.addEventListener('click',function(e){ document.body.removeChild(this); });
  var margin = 16, width = window.innerWidth, height = window.innerHeight;
  var obradiv = newel('div'); projector.appendChild(obradiv);
  obradiv.style.margin = margin+'px'; height -= 2*margin; width -= 2*margin;
  obradiv.style.height = height + 'px';
  var obrazek = svg.cloneNode(true); obradiv.appendChild(obrazek);
  if (width >= height) {obrazek.setAttribute('height',height+'px'); obrazek.setAttribute('width','100%');}
  else {obrazek.setAttribute('height','100%'); obrazek.setAttribute('width',width+'px');}
  obrazek.style.display = 'block'; obrazek.style.marginLeft = 'auto'; obrazek.style.marginRight = 'auto';
  obrazek.style.cursor = 'not-allowed';
  var wroc = newel('button'); wroc.innerHTML = '&#10060;';
  wroc.style.position = 'absolute'; wroc.style.right = '0'; wroc.style.top = '0';
  projector.appendChild(wroc);
  if (url)
  {
	  var zagraj = newel('button'); zagraj.innerHTML = playagainstcomputertext();
	  zagraj.addEventListener('click',function(e){ clickgraj(url,fen,flipcyfra); gawyklad('play','projector'); } );
	  zagraj.style.position = 'absolute'; zagraj.style.left = '0'; zagraj.style.top = '0';
	  projector.appendChild(zagraj);
	  var cwicz = newel('button'); cwicz.innerHTML = cwiczbezkompatext();
	  cwicz.addEventListener('click',function(e){ clickgraj(url,fen,flipcyfra,true); gawyklad('practice','projector'); } );
	  cwicz.style.position = 'absolute'; cwicz.style.left = (zagraj.clientWidth+8)+'px'; cwicz.style.top = '0';
	  projector.appendChild(cwicz);
  }
 };
 svg.addEventListener('click',clickdiagram);
 svg.style.cursor = 'pointer';
 return svg;
}

function svgdiagram(width,height,fen,flip,size,kwadraciki,arrows,white2move,black2move,topt,topsize,bott,botsize,graj,prefix)
{
	function czygraj()
	{
	 if (width != '8' || height != '8') return false;
	 //if (fen.indexOf('K')==-1 || fen.indexOf('k')==-1) return false;
	 if (graj == 'niema') return false;
	 return true;
	}
 var poza = pozafromFEN_svgram(fen,width,height);
 var style = 'merida';
 var darkR = 13*16, darkG = 13*16, darkB = 13*16, lightR = 15*16, lightG = 15*16, lightB = 15*16;
 var rimfill = '#ffffff', borderfill = 'rgb(150,150,150)', textc = 'rgb(124,124,124)';
 var rimgramm = rimgram(poza,width,height,flip,size,style,darkR,darkG,darkB,lightR,lightG,lightB,kwadraciki,arrows,rimfill,borderfill,textc,white2move,black2move); 
 var toptc = 'black', bottc = 'black';
 var textgramm = textgram(rimgramm,topt,topsize,toptc,bott,botsize,bottc,'white');
 
 if (!czygraj()) return projectorgram(textgramm);
 
 var flipcyfra = (flip) ? '1' : '0';
 var svg = projectorgram(textgramm,prefix,fen,flipcyfra);
 var div = newel('div'); div.style.display = 'inline-block'; div.style.position = 'relative'; div.appendChild(svg);
 var wizboardpath = prefix;
 
 var grajbutton = newel('button');
 var zagraj = 'clickgraj("' + wizboardpath + '","' + fen + '","' + flipcyfra + '");';
 zagraj += 'gawyklad("play","svgram");';
 grajbutton.setAttribute('onclick',zagraj);
 grajbutton.innerHTML = playtext(); // $tooltip = "title='zagraj z komputerem'";
 grajbutton.setAttribute('style',"padding:0; margin:0; line-height:1; outline:none; font-size:50%; position:absolute; left:-2em;");
 if (topt!='') grajbutton.style.top = topsize+"px";
 
 var cwiczbutton = newel('button');
 var pocwicz = 'clickgraj("' + wizboardpath + '","' + fen + '","' + flipcyfra + '",true);';
 pocwicz += 'gawyklad("practice","svgram");';
 cwiczbutton.setAttribute('onclick',pocwicz); // $tooltip = "title='poćwicz sam lub z kimś innym'";
 cwiczbutton.innerHTML = cwicztext();
 cwiczbutton.setAttribute('style',"padding:0; margin:0; line-height:1; outline:none; font-size:50%; position:absolute; left:-2em;");
 var margin = 20;
 if (topt!='') cwiczbutton.style.top = (parseInt(topsize)+margin)+"px"; else cwiczbutton.style.top = margin+'px';
 
 div.appendChild(grajbutton); div.appendChild(cwiczbutton);
 return div;
}



// animated diagram

function arrowsarray(pozas,width,height)
{
	function squareIndex(x,y,width,height,flip)
	{
	 var sqi = (y-1)*width + x;
	 if (flip) return width*height+1-sqi;
	 return sqi; 
	}
	function count(a) { return a.length; }
	var ile = pozas.length;
	var arrows = [[]]; //arrows[] = array();
	var arcol = 'Q128Q128Q128';
	if (ile>=2) for (var i=1; i < ile; i++)
	{
	 var zniklo = [];//array();
	 var nowystoi = [];//array();
	 for (var x=1; x<=width; x++) for (var y=1; y<=height; y++)
	 {
	  var n = squareIndex(x,y,width,height,false);
	  if (pozas[i-1][n]!='_' && pozas[i][n]=='_') zniklo.push( [x,y] );
	  else if (pozas[i-1][n] != pozas[i][n]) nowystoi.push( [x,y] );
	 }
	 if (count(nowystoi)==0 || count(zniklo)==0)
	 {
	  arrows.push( [] );
	 }
	 else if (count(zniklo)==1)
	 {
	  var x1 = zniklo[0][0];
	  var y1 = zniklo[0][1];
	  var x2 = nowystoi[0][0];
	  var y2 = nowystoi[0][1];
	  arrows.push( [ x1+'Q'+y1+'Q'+x2+'Q'+y2+arcol ] );
	 }
	 else
	 {
	  var arr = [];
	  for (var x=0; x<count(zniklo); x++) for (var y=0; y<count(nowystoi); y++)
	  {
	   var from = zniklo[x];
	   var cozniklo = pozas[i-1][squareIndex(from[0],from[1],width,height,false)];
	   var to = nowystoi[y];
	   var costoi = pozas[i][squareIndex(to[0],to[1],width,height,false)];
	   if (cozniklo == costoi) arr.push( [from,to] );
	  }
	  if (count(arr)==0 || count(arr)>2)
	  {
	   arrows.push( [] );
	  } 
	  else if (count(arr)==1) // enpassant
	  {
	   var x1 = arr[0][0][0];
	   var y1 = arr[0][0][1];
	   var x2 = arr[0][1][0];
	   var y2 = arr[0][1][1];
	   arrows.push( [ x1+'Q'+y1+'Q'+x2+'Q'+y2+arcol ] );
	  }
	  else // castling
	  {
	   var x1 = arr[0][0][0];
	   var y1 = arr[0][0][1];
	   var x2 = arr[0][1][0];
	   var y2 = arr[0][1][1];
	   var costoi = pozas[i][squareIndex(x2,y2,width,height,false)];
	   if (costoi != 'K' && costoi != 'k')
	   {
		x1 = arr[1][0][0];
		y1 = arr[1][0][1];
		x2 = arr[1][1][0];
		y2 = arr[1][1][1];
	   }
	   costoi = pozas[i][squareIndex(x2,y2,width,height,false)];
	   if (costoi != 'K' && costoi != 'k')
	   {
		arrows.push( [] );
	   }
	   else
	   {
		arrows.push( [ x1+'Q'+y1+'Q'+x2+'Q'+y2+arcol ] );
	   }
	  }
	 }
	}
	return arrows;
}






function extract_FEN_from_PGN(pgn)
{
 var FEN_tag = /\[FEN "(.*)"\]/;
 var found = pgn.match(FEN_tag);
 if (found) return found[1]; else return 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1';
}

function array_of_lines_from_PGN(pgn)
{		
	function tokens(pgn) // returns an array of tokens which are either moves without numbers or brackets () if the PGN is valid
	{
	 var a = pgn.substring(0);
	 	 
	 var comment = /\{[^\{\}]{4,}\}/g; // {no braces inside braces}; leaves in short comments: {=}, {oo}, {+=}, {=+}, {+/-}, {-/+}, {+-}, {-+}
	 a = a.replace(comment,' ');
	 
	 var PGNtag = /\[\D[^\[\]]*\]/g;
	 a = a.replace(PGNtag,' ');
	 
	 a = a.replace('[','(');
	 a = a.replace(']',')');

	 a = a.replace(/\*/g,' ');  // remove stars *
	 
	 var movenumber =  /[0-9]+\.+/g;
	 a = a.replace(movenumber,' ');
	 
	 var closingbracket = /\)/g;
	 a = a.replace(closingbracket,' )');

	 var beginningwhitespace = /^\s+/g;
	 a = a.replace(beginningwhitespace,'');

	 var finalwhitespace = /\s+$/;
	 a = a.replace(finalwhitespace,'');

	 var widespace = /\s+/g;
	 a = a.replace(widespace,'|');

	 return a.split('|');
	}

	function lineate(a,start)
	{
	 start = start || 0;
	 var N = a.length; // the number of tokens in the array
	 var ret = []; // preparing to return an array of lines
	 var head = '';
	 var lastmove = '';
	 for (var i = start; i<N; i++)
	 {
	  var token = a[i];
	  if (token == ')')
	  {
	   ret.push(head);
	   return [i,ret]; // returning a temporary answer to be processed recursively before the final return
	  }
	  if (token == '(')
	  {
	   var aa = lineate(a,i+1);
	   i = aa[0];
	   var tails = aa[1];
	   for (var k=0; k < tails.length; k++) ret.push( head + tails[k] );
	   if (i+1==N || a[i+1]!='(') head += lastmove + ' ';
	  }
	  else 
	  {
	   if (i+1==N || a[i+1]!='(') head += token + ' ';
	   else lastmove = token;
	  }
	 }
	 ret.push(head)
	 return ret;
	}

 var ret;
 try { ret = lineate(tokens(pgn)); } catch(err) { console.log(err); return ['PGN ERROR']; }	
 return ret;
}
function initfen(fen)
{
	moveNumber = 1;
	EnsureAnalysisStopped();
	ResetGame();
	if (InitializeBackgroundEngine()) { g_backgroundEngine.postMessage("go"); }
	g_allMoves = [];		
	var result = InitializeFromFen(fen);
	if (result.length != 0) {}
	g_allMoves = [];
	EnsureAnalysisStopped();
	InitializeBackgroundEngine();
	g_playerWhite = !!g_toMove;
	g_fens[0] = fen;
}

function mate2_filteritems(items)
{
	function isUniqueMate2(fen)
	{
		initfen(fen);
		if (matelines(1).length > 0) return false;
		var lines = matelines(2), ile = lines.length;
		if (ile == 0) return false;
		if (ile == 1) return true;
		var keymove = lines[0][0];
		for (var i=0; i<ile; i++) if (lines[i][0] != keymove) return false;
		return true;
	}
	var ile = items.length;
	if (ile == 0) return [];
	var ret = [];
	for (var i=0; i < ile && ret.length < 7; i++)
	{
		var item = items[i];
		if (isUniqueMate2(item.FEN)) ret.push(item);
		else console.log("Found a flawed FEN that is not a unique mate in 2: "+item.FEN);
	}
	return ret;
}



function ktonaruchu(fen)
{
	if (isRussian()) return (fen.indexOf(' w ')>-1) ? 'Белые' : 'Чёрные';
	if (isPolish()) return (fen.indexOf(' w ')>-1) ? 'Białe' : 'Czarne';
	return (fen.indexOf(' w ')>-1) ? 'White' : 'Black';
}
function ktomatujew2(fen)
{
	var matuje = 'mates in 2 moves';
	if (isRussian()) matuje =  'ставят мат в 2 хода';
	if (isPolish()) matuje = 'dają mata w 2 ruchach';
	return ktonaruchu(fen) + ' ' + matuje + '.';
}
function ktomatujew1(fen)
{
	var matuje = 'mates in 1';
	if (isRussian()) matuje =  'ставят мат в 1 ход';
	if (isPolish()) matuje = 'dają mata w jednym';
	return ktonaruchu(fen) + ' ' + matuje + '.';
}

// the following functions use the global variables wizboardpath
function link2avoid(fen)
{
	var p = 'p=1'+fen.split(' ').join('_').split('/').join('X'); 
	var href = 'https://www.apronus.com/chess/puzzles/mate-in-1-avoid/?' + p;
	if (wizboardpath.indexOf('https://www.apronus.com') == -1) href = 'https://agnes-bruckner.com/chesspuzzles/mate-in-1-avoid/?' + p;
	var a = newel('a'); a.href = href; a.innerHTML = 'Avoid mate in 1'; a.target = '_blank';
	return a;
}

function link2mate1(fen)
{
	var p = 'p=1'+fen.split(' ').join('_').split('/').join('X');
	var href = 'https://www.apronus.com/chess/puzzles/mate-in-1-problems/?' + p;
	if (wizboardpath.indexOf('https://www.apronus.com') == -1) href = 'https://agnes-bruckner.com/chesspuzzles/mate-in-1-problems/?' + p;
	var a = newel('a'); a.href = href; a.innerHTML = 'Mate in 1'; a.target = '_blank';
	return a;				
}
function also_generates_h6(fen)
{
	var h6 = newel('h6'); h6.innerHTML = 'Related easier sets: ';
	h6.appendChild(link2avoid(fen)); h6.appendChild(document.createTextNode(' and ')); h6.appendChild(link2mate1(fen));
	//h6.appendChild(document.createTextNode(' puzzle sets.'));
	return h6;
}
function mate2_header(fen,include_h6)
{
	var h3 = newel('h3'); h3.innerHTML = ktomatujew2(fen);
	var div = newel('div');
	div.appendChild(h3);
	if (include_h6) div.appendChild(also_generates_h6(fen));
	return div;
}
function mate2_source(fen)
{
	var lang = ''; if (isRussian()) lang = '&lang=ru'; if (isPolish()) lang = '&lang=pl';
	var header_html = mate2_header(fen,true).innerHTML;
	var sourcepuzzle = '?p=0' + fen.split(' ').join('_').split('/').join('X') + '&M=2&m=256&h=' + encodeURIComponent(header_html);
	var prefix = 'https://www.apronus.com/chess/puzzle/';
	if (wizboardpath.indexOf('https://www.apronus.com') == -1) prefix = wizboardpath + 'puzzle-index.php';
	var sourceURL = prefix + sourcepuzzle + lang;
	var a = newel('a'); a.href = sourceURL; a.target = '_blank';
	var mate2 = 'Mate in 2';
	if (isRussian()) mate2 = 'Мат в 2 хода';
	if (isPolish()) mate2 = 'Mat w 2';
	a.innerHTML = mate2;
	var div = newel('div');
	if (isRussian()) { div.innerHTML = 'На основании композиции '; div.appendChild(a); div.innerHTML += '.'; }
	else if (isPolish()) { div.innerHTML = 'Na podstawie kompozycji '; div.appendChild(a); div.innerHTML += '.'; }
	else { div.innerHTML = 'From a composition '; div.appendChild(a); div.innerHTML += '.'; }
	return div;
}
function mate1_header(fen)
{
 const include_h6 = () =>
 {
  try { global_include_h6 } catch { return true; } // default behavior for years
  return global_include_h6; // for 2vs1 puzzles, 2022-07-06
 }

	var div = newel('div');
	var h3 = newel('h3'); h3.innerHTML = ktomatujew1(fen);
	var h6 = newel('h6'); h6.appendChild(mate2_source(fen));
	div.appendChild(h3);
        if (include_h6()) div.appendChild(h6);
	return div;	
}
function avoid1_header(fen,ile)
{
 const include_h6 = () =>
 {
  try { global_include_h6 } catch { return true; } // default behavior for years
  return global_include_h6; // for 2vs1 puzzles, 2022-07-06
 }

	var findall = "Find "+ile+" Black moves that avoid mate in 1.";
	var findone = "Black to play. Avoid mate in one.";
	var find = (ile == 1) ? findone : findall;
	if (isPolish()) find = 'Znajdź wszystkie ruchy czarnych, które unikają mata w jednym ruchu.';
	if (isRussian()) find = 'Найти все ходы, которые избегают мат в один ход.';
	if (isPolish() && (ile === 1))
	{
		find = 'Ruch czarnych. Uniknij mata w jednym.';
	}

	var h3 = newel('h3'); h3.innerHTML = find;
	var h6 = newel('h6'); h6.appendChild(mate2_source(fen));
	var div = newel('div'); div.appendChild(h3); 
	if (include_h6()) div.appendChild(h6);
	return div;		
}

function mate2_items2puzzleset(items,include_h6)
{
	function item2puzzle(item)
	{
		var fen = item.FEN;
		var flipcyfra = (fen.indexOf(' w ') > -1) ? '0' : '1';
		var transferfen = flipcyfra + fen.split(' ').join('_').split('/').join('X');
		var solution = item.solution;
		var puzzle = '?p=' + transferfen + '&M=2&m=256&h='+encodeURIComponent(mate2_header(fen,include_h6).innerHTML);
		return puzzle;
	}
	var ile = items.length;
	if (ile == 0) return [];
	var puzzleset = [1];
	for (var i=1; i <= ile; i++) puzzleset.push(item2puzzle(items[i-1]));
	return puzzleset;
}

function mate1_item2puzzleset(item)
{	
	var puzzleset = [1];
	initfen(item.FEN);
	var lines = smartlines(matelines(2));
	if (lines.length == 0)
	{
		alert('ERROR. No forced mate in 2 in this position from the database.');
		return [];
	}
	var keymove = lines[0][0];
	MakeMove(keymove);
	for (var i=0; i < lines.length; i++)
	{
		var response = lines[i][1];
		MakeMove(response);
		var puzzlefen = GetFen() + ' 0 1';
		
		var flipcyfra = (puzzlefen.indexOf(' w ') > -1) ? '0' : '1';
		var transferfen = flipcyfra + puzzlefen.split(' ').join('_').split('/').join('X');
		var puzzle = '?p=' + transferfen + '&M=1&m=256&h='+encodeURIComponent(mate1_header(item.FEN).innerHTML);
		puzzleset.push(puzzle);
		UnmakeMove(response);
	}
	return puzzleset;
}

/*
function items2puzzleset_avoid(items,max_ile_ruchow)
{
	var ile = items.length;
	if (ile == 0) return [];
	do
	{
		for (var i=0; i < ile; i++)
		{
			var puzzleset = avoid1_item2puzzleset(items[i],max_ile_ruchow);
			if (puzzleset && puzzleset.length > 1) return puzzleset;
		}
		max_ile_ruchow++;
	}
	while(max_ile_ruchow < 100);
	console.log("Cannot find an avoid mate in 1 puzzle.");
	return [];
}
*/
/*
function items2puzzleset_avoid(items,max_ile_ruchow)
{
	var ile = items.length;
	if (ile == 0) return [];
	var puzzles = [];
	do
	{
		for (var i=0; i < ile && puzzles.length < 7; i++)
		{
			var puzzleset = avoid1_item2puzzleset(items[i],max_ile_ruchow);
			if (puzzleset && puzzleset.length > 1)
			{
				puzzleset.shift();
				puzzles = puzzles.concat(puzzleset);
			}
		}
		max_ile_ruchow++;
	}
	while(max_ile_ruchow < 6 && puzzles.length < 7 );
	return [1].concat(puzzles);
}
*/
function items2puzzleset_avoid(items,max_ile_ruchow)
{
	var ile = items.length;
	if (ile == 0) return [];
	var puzzles = [];
	do
	{
		for (var i=0; i < ile; i++)
		{
			var puzzleset = avoid1_item2puzzleset(items[i],max_ile_ruchow);
			if (puzzleset && puzzleset.length > 1)
			{
				puzzleset.shift();
				puzzles = puzzles.concat(puzzleset);
			}
		}
		max_ile_ruchow++;
	}
	while(max_ile_ruchow < 6 && puzzles.length < 7 );
	return [1].concat(select_randomly_N_elements_from_Array(7,puzzles));
}

function avoid1_item2puzzleset(item,max_ile_ruchow)
{
	var puzzleset = [1];
	initfen(item.FEN);
	var lines = matelines(2);
	if (lines.length == 0)
	{
		alert('ERROR. No forced mate in 2 in this position from the database.');
		return null;
	}
	var keymove = lines[0][0];
	var moves = GenerateValidMoves();
	for (var j = moves.length-1; j>=0; j--)
	{
		var firstmove = moves[j];
		if (firstmove != keymove)
		{
			MakeMove(firstmove);
			var matethreat = false;
			if (!g_inCheck)
			{
				g_toMove = 8-g_toMove;
				matethreat = (matelines(1).length > 0);
				g_toMove = 8-g_toMove;
			}
			if (matethreat)
			{
				var responses = GenerateValidMoves();
				if (responses.length > 1)
				{
					var transferfen = ('1'+GetFen()+" 0 1").split(' ').join('_').split('/').join('X');
					var w = '';
					for (var i = responses.length-1; i>=0; i--)
					{
						var response = responses[i];
						MakeMove(response);
						var good = matelines(1).length == 0;
						UnmakeMove(response);
						if (good) w += zakoduj(moveindex(response)) + '-';
					}
					w = w.substring(0,w.length-1);
					var mode = 3 + 256;// + 8; // 8 show solution
					var ileruchow = w.split('-').length;
					if (ileruchow <= max_ile_ruchow)
					{
						var header_html = avoid1_header(item.FEN,ileruchow).innerHTML;
						var puzzle = '?p=' + transferfen + '&w=' + w + '&m='+mode + '&h='+encodeURIComponent(header_html);
						puzzleset.push(puzzle);
					}
				}
			}
			else // no mate threat
			{
				var responses = GenerateValidMoves();
				if (responses.length > 1)
				{
					var ilegood = 0;
					var w = '';
					for (var i = responses.length-1; i>=0; i--)
					{
						var response = responses[i];
						MakeMove(response);
						var good = matelines(1).length == 0;
						UnmakeMove(response);
						if (good)
						{
							ilegood++;
							w += zakoduj(moveindex(response)) + '-';
						}
					}
					if (ilegood == 1 && ilegood < responses.length)
					{
						w = w.substring(0,w.length-1);
						var transferfen = ('1'+GetFen()+" 0 1").split(' ').join('_').split('/').join('X');
						var mode = 3 + 256;// + 8; // 8 show solution
						var header_html = avoid1_header(item.FEN,1).innerHTML;
						var puzzle = '?p=' + transferfen + '&w=' + w + '&m='+mode+ '&h=' + encodeURIComponent(header_html);
						puzzleset.push(puzzle);
					}
				}
			}
			UnmakeMove(firstmove);
		}
	}
	return puzzleset;
}

/*
function avoid1_item2puzzleset(item,max_ile_ruchow)
{
	var puzzleset = [1];
	initfen(item.FEN);
	var lines = matelines(2);
	if (lines.length == 0)
	{
		alert('ERROR. No forced mate in 2 in this position from the database.');
		return null;
	}
	var keymove = lines[0][0];
	var moves = GenerateValidMoves();
	for (var j = moves.length-1; j>=0; j--)
	{
		var firstmove = moves[j];
		if (firstmove != keymove)
		{
			MakeMove(firstmove);
			var matethreat = false;
			if (!g_inCheck)
			{
				g_toMove = 8-g_toMove;
				matethreat = (matelines(1).length > 0);
				g_toMove = 8-g_toMove;
			}
			if (matethreat)
			{
				var responses = GenerateValidMoves();
				if (responses.length > 0) // not stalemate
				{
					var transferfen = ('1'+GetFen()+" 0 1").split(' ').join('_').split('/').join('X');
					var w = '';
					for (var i = responses.length-1; i>=0; i--)
					{
						var response = responses[i];
						MakeMove(response);
						var good = matelines(1).length == 0;
						UnmakeMove(response);
						if (good) w += zakoduj(moveindex(response)) + '-';
					}
					w = w.substring(0,w.length-1);
					var mode = 3 + 256;// + 8; // 8 show solution
					var ileruchow = w.split('-').length;
					if (ileruchow <= max_ile_ruchow)
					{
						var header_html = avoid1_header(item.FEN,ileruchow).innerHTML;
						var puzzle = '?p=' + transferfen + '&w=' + w + '&m='+mode + '&h='+encodeURIComponent(header_html);
						puzzleset.push(puzzle);
					}
				}
			}
			else // no mate threat
			{
				var responses = GenerateValidMoves();
				if (responses.length > 0)
				{
					var ilegood = 0;
					var w = '';
					for (var i = responses.length-1; i>=0; i--)
					{
						var response = responses[i];
						MakeMove(response);
						var good = matelines(1).length == 0;
						UnmakeMove(response);
						if (good)
						{
							ilegood++;
							w += zakoduj(moveindex(response)) + '-';
						}
					}
					if (ilegood == 1 && ilegood < responses.length)
					{
						w = w.substring(0,w.length-1);
						var transferfen = ('1'+GetFen()+" 0 1").split(' ').join('_').split('/').join('X');
						var mode = 3 + 256;// + 8; // 8 show solution
						var header_html = avoid1_header(item.FEN,1).innerHTML;
						var puzzle = '?p=' + transferfen + '&w=' + w + '&m='+mode+ '&h=' + encodeURIComponent(header_html);
						puzzleset.push(puzzle);
					}
				}
			}
			UnmakeMove(firstmove);
		}
	}
	return puzzleset;
}
*/

function mate1games_items2puzzleset(items)
{
	function item2puzzle(item)
	{
		function finalFEN()
		{
			function playmove(ruch)
			{
			 var moves = GenerateValidMoves();
			 var move = null;
			 for (var i=0; i<moves.length; i++)
			  if (GetMoveSAN(moves[i]).indexOf(ruch)==0) move = moves[i];
			 if (move == null) { alert('ERROR: cannot play '+ruch); return; }
			 g_allMoves[g_allMoves.length] = move;
			 MakeMove(move);
			}
			var fen = item.FEN;
			var moves = sameruchyarray(item.solution);
			moveNumber = 1;
			EnsureAnalysisStopped();
			ResetGame();
			if (InitializeBackgroundEngine()) { g_backgroundEngine.postMessage("go"); }
			g_allMoves = [];		
			var result = InitializeFromFen(fen);
			if (result.length != 0) {}
			g_allMoves = [];
			EnsureAnalysisStopped();
			InitializeBackgroundEngine();
			g_playerWhite = !!g_toMove;
			g_fens[0] = fen;
			playmove(moves[0]); playmove(moves[1]);
			return GetFen() + ' 0 1';
		}
		var fen = finalFEN(item);
		var flipcyfra = (fen.indexOf(' w ') > -1) ? '0' : '1';
		var transferfen = flipcyfra + fen.split(' ').join('_').split('/').join('X');
		var solution = item.solution;
		var header_html = ktomatujew1(fen);
		var puzzle = '?p=' + transferfen + '&M=1&m=256&h='+encodeURIComponent(header_html);
		return puzzle;
	}
	var puzzleset = [1];
	var ile = items.length;
	for (var i=1; i <= ile; i++) puzzleset.push(item2puzzle(items[i-1]));
	return puzzleset;
}

function mate1_items2puzzleset(items)
{
	var puzzles = [];
	for (var i=0; i < items.length; i++)
	{
		var a = mate1_item2puzzleset(items[i]);
		a.shift();
		puzzles = puzzles.concat(a);
	}
	return [1].concat(select_randomly_N_elements_from_Array(7,puzzles));
}




function fromgames_avoid1_items_to_puzzleset(items)
{
	function avoid1_fen_to_puzzle(FEN)
	{
		function header(czyWhite)
		{
			var txt = (czyWhite ? 'White' : 'Black') + ' to play. Avoid mate in 1.';
			if (isPolish()) txt = (czyWhite ? 'Ruch białych.' : 'Ruch czarnych.') + ' Uniknij mata w jednym.';
			if (isRussian()) txt = (czyWhite ? 'Ход белых.' : 'Ход чёрных.') + ' Избегайте мат в один ход.';
			return '<h3>'+txt+'</h3>';
		}
		// assuming the side to move mates in 1
		initfen(FEN);
		if (g_inCheck) { console.log('king capture error in fen: ', FEN); return null; }
		g_toMove = 8-g_toMove;
		var moves = GenerateValidMoves();
		if (moves.length < 2) return null;
		var successmoves = [], ilefailuremoves = 0;
		for (var i = moves.length-1; i>=0; i--)
		{
			var move = moves[i];
			MakeMove(move);
			if (matelines(1).length) ilefailuremoves++; else successmoves.push(move);
			UnmakeMove(move);
		}
		if (ilefailuremoves == 0 || ilefailuremoves == moves.length) return null;
		var puzzlefen = GetFen() + ' 0 1';
		var flipcyfra = (puzzlefen.indexOf(' w ') > -1) ? '0' : '1';
		var transferfen = flipcyfra + puzzlefen.split(' ').join('_').split('/').join('X');
		var w = '';
		for (var i=successmoves.length-1; i>=0; i--) w += zakoduj(moveindex(successmoves[i])) + '-';
		w = w.substring(0,w.length-1);
		var czyWhite = puzzlefen.indexOf(' w ') > -1;
		var puzzle = '?p=' + transferfen + '&w='+ w + '&m=257&h='+encodeURIComponent(header(czyWhite));
		return puzzle;
	}

	function finalFEN(item)
	{
		function playmove(ruch)
		{
		 var moves = GenerateValidMoves();
		 var move = null;
		 for (var i=0; i<moves.length; i++)
		  if (GetMoveSAN(moves[i]).indexOf(ruch)==0) move = moves[i];
		 if (move == null) { alert('ERROR: cannot play '+ruch); return; }
		 g_allMoves[g_allMoves.length] = move;
		 MakeMove(move);
		}
		var fen = item.FEN;
		var moves = sameruchyarray(item.solution);
		initfen(fen);
		playmove(moves[0]); playmove(moves[1]);
		return GetFen() + ' 0 1';
	}

	var ile = items.length;
	if (ile == 0) return [];
	var puzzles = [];
	for (var i=0; i < ile; i++)
	{
		var puzzle = avoid1_fen_to_puzzle(finalFEN(items[i]));
		if (puzzle) puzzles.push(puzzle);
	}
	return [1].concat(select_randomly_N_elements_from_Array(7,puzzles));
}













