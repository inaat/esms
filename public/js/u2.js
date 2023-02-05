/*
Copyright (c) Urdu.ca - 2018
*/

$(document).ready(function() {

    $(".urdu_input").on("keypress", function (e) {
        var doc = $(this);
         
            if (8 == e.keyCode || 13 == e.keyCode) {
               return true;
            } else {
              e.preventDefault();
              var newString = $(this).val() + changeKey(e);
              $(this).val(newString);
            }
  
  
    });
  
    function changeKey(evt) {
  
      var keyCode = evt.keyCode ? evt.keyCode :
        evt.charCode ? evt.charCode :
        evt.which ? evt.which : void 0;
      var key;
      if (keyCode) {
        key = String.fromCharCode(keyCode);
        switch(key) {
          case ' ': return ' ';
          case '!': return '!';
          case ':': return ':';
          case '?': return String.fromCharCode(1567);
          case '+': return String.fromCharCode(1570);
          case 'A': return String.fromCharCode(1619);
          case 's': return String.fromCharCode(1587);
          case 'S': return String.fromCharCode(1589);
          case 'd': return String.fromCharCode(1583);
          case 'D': return String.fromCharCode(1672);
          case 'f': return String.fromCharCode(1601);
          case 'g': return String.fromCharCode(1711);
          case 'G': return String.fromCharCode(1594);
          case 'h': return String.fromCharCode(1726);
          case 'H': return String.fromCharCode(1581);
          case 'j': return String.fromCharCode(1580);
          case 'J': return String.fromCharCode(1590);
          case 'k': return String.fromCharCode(1705);
          case 'K': return String.fromCharCode(1582);
          case 'l': return String.fromCharCode(1604);
          case 'L': return String.fromCharCode(1554);
          case 'z': return String.fromCharCode(1586);
          case 'Z': return String.fromCharCode(1584);
          case 'x': return String.fromCharCode(1588);
          case 'X': return String.fromCharCode(1688);
          case 'c': return String.fromCharCode(1670);
          case 'C': return String.fromCharCode(1579);
          case 'v': return String.fromCharCode(1591);
          case 'V': return String.fromCharCode(1592);
          case 'a': return String.fromCharCode(1575);
          case 'b': return String.fromCharCode(1576);
          case 'B': return String.fromCharCode(1555);
          case 'n': return String.fromCharCode(1606);
          case 'N': return String.fromCharCode(1722);
          case 'm': return String.fromCharCode(1605);
          case 'q': return String.fromCharCode(1602);
          case 'w': return String.fromCharCode(1608);
          case 'W': return String.fromCharCode(65018);
          case 'e': return String.fromCharCode(1593);
          case 'E': return String.fromCharCode(1553);
          case 'r': return String.fromCharCode(1585);
          case 'R': return String.fromCharCode(1681);
          case 't': return String.fromCharCode(1578);
          case 'T': return String.fromCharCode(1657);
          case 'y': return String.fromCharCode(1746);
          case 'Y': return String.fromCharCode(1537);
          case 'u': return String.fromCharCode(1574);
  
          case 'o': return String.fromCharCode(1729);
          case 'p': return String.fromCharCode(1662);
          case 'i': return String.fromCharCode(1740);
          case 'O': return String.fromCharCode(1731);
          case 'I': return String.fromCharCode(1648);
          case '$': return String.fromCharCode(1569);
          case '0': return String.fromCharCode(1776);
          case '1': return String.fromCharCode(1777);
          case '2': return String.fromCharCode(1778);
          case '3': return String.fromCharCode(1779);
          case '4': return String.fromCharCode(1780);
          case '5': return String.fromCharCode(1781);
          case '6': return String.fromCharCode(1782);
          case '7': return String.fromCharCode(1783);
          case '8': return String.fromCharCode(1784);
          case '9': return String.fromCharCode(1785);
  
          case '.': return String.fromCharCode(1748);
          case '\'': return String.fromCharCode(1748);
          case '\"': return String.fromCharCode(1600);
          case ';': return String.fromCharCode(1563);
          case '-': return String.fromCharCode(1652);
          case 'P': return String.fromCharCode(1615);
          case '<': return String.fromCharCode(1616);
          case '>': return String.fromCharCode(1614);
          case '=': return String.fromCharCode(1572);
          case '*': return String.fromCharCode(1612);
          case '~': return String.fromCharCode(1611);
  
          case '`': return String.fromCharCode(1613);
          case '_': return String.fromCharCode(1617);
          case 'Q': return String.fromCharCode(1618);
          case '/': return String.fromCharCode(1618);
          case '@': return String.fromCharCode(1548);
          case '#': return String.fromCharCode(1549);
          case '%': return String.fromCharCode(1610);
          case '^': return String.fromCharCode(1552);
  
          case '-': return String.fromCharCode(1571);
          case 'U': return String.fromCharCode(1623);
          case '{': return String.fromCharCode(8216);
          case '}': return String.fromCharCode(8217);
          case '\\': return String.fromCharCode(1550);
          case '|': return String.fromCharCode(1556);
          case 'F': return String.fromCharCode(1622);
          case 'M': return String.fromCharCode(1573);
  
          case ',': return String.fromCharCode(1548);
  
          case '(': return '(';
          case ')': return ')';
          case '[': return '[';
          case ']': return ']';
  
  
          /*default: return '';*/
          default: return key;
                
          /*default: return String.fromCharCode(keyCode);*/
  
        }
  
      }
    }
  
  });