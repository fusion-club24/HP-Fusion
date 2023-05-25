/* == CSS-Classes: Reihenfolge: 1, 2, 3, 4, 6 (rating/Bewertung) == */
var P91Styles = new Array("P91PWS_1", "P91PWS_2", "P91PWS_3", "P91PWS_4", "P91PWS_6");
 
/* == CSS-Class der Ausgabe: == */
var P91Output = ("P91PWS_O");
 
/* == Kriterien-Einschätzung == */
/* 0 = Sehr kritisch
 * 1 = Alltagsform
 */
var P91mode = 1;
 
/* == Erlaubte Sonderzeichen (RegExp) == */
/* null = für alle Zeichen außer 0-9A-Za-zts   */
var P91valid = null;
 
/* Beispiel für die Sonderzeichen _-.            */
// var P91valid = /[._-]/;
 
/* == Ausgabe: Reihenfolge: 1, 2, 3, 4, 6 (rating/Bewertung) ==*/
/* Deutsch */
var P91Msg = new Array("Sehr sicher", "Sicher", "Mittel", "Schwach", "Sehr schlecht");
 
/* English */
// var P91Msg = new Array("Very Secure", "Secure", "Average", "Weak", "Very Weak");
 
/*
~~~~ ENDE ~~~~
*/
function P91PWS(heuri) {
	var P91b = new Array(0,0,0,0);
	var P91r = new Array(0,0,0,0);
	var P91various = 1;
	var sum = 0;
	var erg = null;
	var P91PWS = document.getElementById("P91PWS"); 
	var heuri = heuri.split('');
	for(i = 0; i <= (heuri.length - 1); i++) {
		if (heuri[i] != null) {
			// LOW
			if (heuri[i].match(/([a-z])/)) {
				P91b[0]++;
				if (i > 0) {
					if (!heuri[(i - 1)].match(/([a-z])/)) {
						P91various++;
					}
				}
			}
			// UP
			if (heuri[i].match(/([A-Z])/)) {
				P91b[1]++;
				if (i > 0) {
					if (!heuri[(i - 1)].match(/([A-Z])/)) {
						P91various++;
					}
				}
			}
			// NUM
			if (heuri[i].match(/([0-9])/)) {
				P91b[2]++;
				if (i > 0) {
					if (!heuri[(i - 1)].match(/([0-9])/)) {
						P91various++;
					}
				}
			}
			// SON
			if (P91valid == null) {
				if (heuri[i].match(/[^a-zA-Z0-9ts]/)) {
					P91b[3]++;
					P91various++;
				}
			} else {
				if (heuri[i].match(P91valid)) {
					P91b[3]++;
					P91various++;
				}
			}
		}
    }
	sum = (P91b[0] + P91b[1] + P91b[2] + P91b[3]);
	if(sum < 1)  {
		sum = 1;
	}
	// Bewertung
	P91r[4] = Math.round(P91various * 100 / sum);
	P91r[3] = Math.round(P91b[3] * 100 / sum);
	P91r[2] = Math.round(P91b[2] * 100 / sum);
	P91r[1] = Math.round(P91b[1] * 100 / sum);
	P91r[0] = Math.round(P91b[0] * 100 / sum);  
	// Analyse by PAS
	if(P91r[0] == 100) {
		erg = 4;
	} else if(P91r[0] == 100) {
		erg = 4;
	} else if(P91r[1] == 100) {
		erg = 4;
	}  else if(P91r[2] == 100) {
		erg = 4;
	} else if(P91r[3] == 100 && sum > 5) {
		erg = 0;
	} else 
	if (P91mode == 0) { // hart
		if (sum > 13) {
			if (P91various > 6 || P91b[3] > 4) {
				erg = 0;
			}
			else {
				erg = 1;
			}
		}
		else if (sum > 5 && sum < 14) {
				if (P91various > 5) {
					erg = 2;
				}
				else {
					erg = 3;
				}
			}
			else {
				erg = 4;
			}
	}
	else { // normal
		if (sum > 9) {
			if (P91various > 5 || P91b[3] > 4) {
				erg = 0;
			}
			else {
				erg = 1;
			}
		}
		else if (sum > 5 && sum < 10) {
				if (P91various > 3) {
					erg = 2;
				}
				else {
					erg = 3;
				}
			}
			else {
				erg = 4;
			}
	}	
 
	P91PWS.innerHTML = '<div class="' + P91Output + ' "> ' + P91Msg[erg] +'</div><div class="P91PWS_C"><div class="' + P91Styles[erg] + '"></div></div>';
	return true;
}
