
var arr = [];

function addWord1() {
	var a = document.getElementsByClassName('aWord1')[0];
	a.classList.toggle("visible");
}
function addWord2() {
	var a = document.getElementsByClassName('aWord2')[0];
	a.classList.toggle("visible");
}
function startTraining0(){
	var a = document.getElementsByClassName('tWord0')[0];
	a.classList.toggle("visible");
}
function startTraining1(){
	var a = document.getElementsByClassName('tWord1')[0];
	a.classList.toggle("visible");
}
function startTraining2(){
	var a = document.getElementsByClassName('tWord2')[0];
	a.classList.toggle("visible");
}
function startTraining3(){
	var a = document.getElementsByClassName('tWord3')[0];
	a.classList.toggle("visible");
}
function getLetter(letter, id){
	document.getElementById('inputLet').value += letter;
	document.getElementById(id).style.visibility = "hidden";
	arr.push(id);
}
function clean(){
	document.getElementById('inputLet').value = "";
	for(var i=0; i<arr.length; i++) {
		document.getElementById(arr[i]).style.visibility = "visible";
	}

}

  function talk (a) {
  	var synth = window.speechSynthesis;
	var utterance = new SpeechSynthesisUtterance(a);
	utterance.lang = 'en-US';
    synth.speak (utterance);
  }