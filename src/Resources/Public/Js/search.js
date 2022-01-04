// Add EventListeners to all buttons in #left-side
document.getElementById("gender").addEventListener("click", C1);
document.getElementById("gender2").addEventListener("click", C2);
document.getElementById("discipline").addEventListener("click", C3);
document.getElementById("level").addEventListener("click", C4);
document.getElementById("age").addEventListener("click", C5);

// logic for Columns 1 (function C1) - 5 ( function C5)
function C1() {
  if (document.getElementById("gender").classList.contains("active")) {
    // do nothing
  } else document.getElementById("gender").classList.add("active");
  if (document.getElementById("first").classList.contains("active")) {
    // do nothing
  } else document.getElementById("first").classList.add("active");

  document.getElementById("gender2").classList.remove("active");
  document.getElementById("discipline").classList.remove("active");
  document.getElementById("level").classList.remove("active");
  document.getElementById("age").classList.remove("active");
  
  document.getElementById("second").classList.remove("active");
  document.getElementById("third").classList.remove("active");
  document.getElementById("fourth").classList.remove("active");
  document.getElementById("fifth").classList.remove("active");

  document.getElementById("line").classList.remove("two", "three", "four", "five");
  if (document.getElementById("line").classList.contains("one")) {
    // do nothing
  } else document.getElementById("line").classList.add("one");
}; 

function C2() {
  if (document.getElementById("gender2").classList.contains("active")) {
    // do nothing
  } else document.getElementById("gender2").classList.add("active");
  if (document.getElementById("second").classList.contains("active")) {
    // do nothing
  } else document.getElementById("second").classList.add("active");

  document.getElementById("gender").classList.remove("active");
  document.getElementById("discipline").classList.remove("active");
  document.getElementById("level").classList.remove("active");
  document.getElementById("age").classList.remove("active");
  
  document.getElementById("first").classList.remove("active");
  document.getElementById("third").classList.remove("active");
  document.getElementById("fourth").classList.remove("active");
  document.getElementById("fifth").classList.remove("active");

  document.getElementById("line").classList.remove("one", "three", "four", "five");
  if (document.getElementById("line").classList.contains("two")) {
    // do nothing
  } else document.getElementById("line").classList.add("two");
};

function C3() {
  if (document.getElementById("discipline").classList.contains("active")) {
    // do nothing
  } else document.getElementById("discipline").classList.add("active");
  if (document.getElementById("third").classList.contains("active")) {
    // do nothing
  } else document.getElementById("third").classList.add("active");

  document.getElementById("gender").classList.remove("active");
  document.getElementById("gender2").classList.remove("active");
  document.getElementById("level").classList.remove("active");
  document.getElementById("age").classList.remove("active");
  
  document.getElementById("first").classList.remove("active");
  document.getElementById("second").classList.remove("active");
  document.getElementById("fourth").classList.remove("active");
  document.getElementById("fifth").classList.remove("active");

  document.getElementById("line").classList.remove("one", "two", "four", "five");
  if (document.getElementById("line").classList.contains("three")) {
    // do nothing
  } else document.getElementById("line").classList.add("three");
};

function C4() {
  if (document.getElementById("discipline").classList.contains("active")) {
    // do nothing
  } else document.getElementById("discipline").classList.add("active");
  if (document.getElementById("fourth").classList.contains("active")) {
    // do nothing
  } else document.getElementById("fourth").classList.add("active");

  document.getElementById("gender").classList.remove("active");
  document.getElementById("gender2").classList.remove("active");
  document.getElementById("discipline").classList.remove("active");
  document.getElementById("age").classList.remove("active");
  
  document.getElementById("first").classList.remove("active");
  document.getElementById("second").classList.remove("active");
  document.getElementById("third").classList.remove("active");
  document.getElementById("fifth").classList.remove("active");

  document.getElementById("line").classList.remove("one", "two", "three", "five");
  if (document.getElementById("line").classList.contains("four")) {
    // do nothing
  } else document.getElementById("line").classList.add("four");
};

function C5() {
  if (document.getElementById("age").classList.contains("active")) {
    // do nothing
  } else document.getElementById("age").classList.add("active");
  if (document.getElementById("fifth").classList.contains("active")) {
    // do nothing
  } else document.getElementById("fifth").classList.add("active");

  document.getElementById("gender").classList.remove("active");
  document.getElementById("gender2").classList.remove("active");
  document.getElementById("discipline").classList.remove("active");
  document.getElementById("level").classList.remove("active");
  
  document.getElementById("first").classList.remove("active");
  document.getElementById("second").classList.remove("active");
  document.getElementById("third").classList.remove("active");
  document.getElementById("fourth").classList.remove("active");

  document.getElementById("line").classList.remove("one", "two", "three", "four");
  if (document.getElementById("line").classList.contains("five")) {
    // do nothing
  } else document.getElementById("line").classList.add("five");
};


// logic for #right-side
// logic for buttons in first column
document.getElementById("btnwoman").addEventListener("click", function() {
  if (this.classList.contains("is-active")) {
    // do nothing
  } else { 
    this.classList.add("is-active");
    document.getElementById("btnman").classList.remove("is-active");
    document.getElementById("1st").innerHTML = "Dame";
    document.getElementById("1st").classList.remove("is-hidden");
  }
  C2(); // switch to C2
});
document.getElementById("btnman").addEventListener("click", function() {
  if (this.classList.contains("is-active")) {
    // do nothing
  } else { 
    this.classList.add("is-active");
    document.getElementById("btnwoman").classList.remove("is-active");
    document.getElementById("1st").innerHTML = "Herr";
    document.getElementById("1st").classList.remove("is-hidden");
  }
  C2(); // switch to C2
});

// logic for buttons in second column
document.getElementById("btnwoman2").addEventListener("click", function() {
  if (this.classList.contains("is-active")) {
    // do nothing
  } else { 
    this.classList.add("is-active");
    document.getElementById("btnman2").classList.remove("is-active");
    document.getElementById("3rd").innerHTML = "Dame";
    document.getElementById("2nd").classList.remove("is-hidden");
    document.getElementById("3rd").classList.remove("is-hidden");
  }
  C3();
});
document.getElementById("btnman2").addEventListener("click", function() {
  if (this.classList.contains("is-active")) {
    // do nothing
  } else { 
    this.classList.add("is-active");
    document.getElementById("btnwoman2").classList.remove("is-active");
    document.getElementById("3rd").innerHTML = "Herr";
    document.getElementById("2nd").classList.remove("is-hidden");
    document.getElementById("3rd").classList.remove("is-hidden");
  }
  C3();
});

// logic for buttons in third column
document.getElementById("btnLatin").addEventListener("click", function() {
  if (this.classList.contains("is-active")) {
    // do nothing
  } else { 
    this.classList.add("is-active");
    document.getElementById("btnStandard").classList.remove("is-active");
    document.getElementById("btn10dance").classList.remove("is-active");
    document.getElementById("5th").innerHTML = "Latein";
    document.getElementById("5th").classList.remove("is-hidden");
    document.getElementById("4th").classList.remove("is-hidden");
  }
  C4();
});
document.getElementById("btnStandard").addEventListener("click", function() {
  if (this.classList.contains("is-active")) {
    // do nothing
  } else { 
    this.classList.add("is-active");
    document.getElementById("btnLatin").classList.remove("is-active");
    document.getElementById("btn10dance").classList.remove("is-active");
    document.getElementById("5th").innerHTML = "Standard";
    document.getElementById("5th").classList.remove("is-hidden");
    document.getElementById("4th").classList.remove("is-hidden");
  }
  C4();
});
document.getElementById("btn10dance").addEventListener("click", function() {
  if (this.classList.contains("is-active")) {
    // do nothing
  } else { 
    this.classList.add("is-active");
    document.getElementById("btnLatin").classList.remove("is-active");
    document.getElementById("btnStandard").classList.remove("is-active");
    document.getElementById("5th").innerHTML = "10 Tänze";
    document.getElementById("5th").classList.remove("is-hidden");
    document.getElementById("4th").classList.remove("is-hidden");
  }
  C4();
});

// logic for buttons in fourth column
document.getElementById("btnJugend").addEventListener("click", function() {
  if (this.classList.contains("is-active")) {
    this.classList.remove("is-active");
    document.getElementById("bcJugend").classList.add("is-hidden");
  } else { 
    this.classList.add("is-active");
    document.getElementById("bcJugend").classList.remove("is-hidden");
  }
});
document.getElementById("btnBreitensport").addEventListener("click", function() {
  if (this.classList.contains("is-active")) {
    this.classList.remove("is-active");
    document.getElementById("bcBreitensport").classList.add("is-hidden");
  } else { 
    this.classList.add("is-active");
    document.getElementById("bcBreitensport").classList.remove("is-hidden");
  }
});
document.getElementById("btnVorturnier").addEventListener("click", function() {
  if (this.classList.contains("is-active")) {
    this.classList.remove("is-active");
    document.getElementById("bcVorturnier").classList.add("is-hidden");
  } else { 
    this.classList.add("is-active");
    document.getElementById("bcVorturnier").classList.remove("is-hidden");
  }
});
document.getElementById("btnD").addEventListener("click", function() {
  if (this.classList.contains("is-active")) {
    this.classList.remove("is-active");
    document.getElementById("bcD").classList.add("is-hidden");
  } else { 
    this.classList.add("is-active");
    document.getElementById("bcD").classList.remove("is-hidden");
  }
});
document.getElementById("btnC").addEventListener("click", function() {
  if (this.classList.contains("is-active")) {
    this.classList.remove("is-active");
    document.getElementById("bcC").classList.add("is-hidden");
  } else { 
    this.classList.add("is-active");
    document.getElementById("bcC").classList.remove("is-hidden");
  }
});
document.getElementById("btnB").addEventListener("click", function() {
  if (this.classList.contains("is-active")) {
    this.classList.remove("is-active");
    document.getElementById("bcB").classList.add("is-hidden");
  } else { 
    this.classList.add("is-active");
    document.getElementById("bcB").classList.remove("is-hidden");
  }
});
document.getElementById("btnA").addEventListener("click", function() {
  if (this.classList.contains("is-active")) {
    this.classList.remove("is-active");
    document.getElementById("bcA").classList.add("is-hidden");
  } else { 
    this.classList.add("is-active");
    document.getElementById("bcA").classList.remove("is-hidden");
  }
});
document.getElementById("btnS").addEventListener("click", function() {
  if (this.classList.contains("is-active")) {
    this.classList.remove("is-active");
    document.getElementById("bcS").classList.add("is-hidden");
  } else { 
    this.classList.add("is-active");
    document.getElementById("bcS").classList.remove("is-hidden");
  }
});

// Slider
var elem = document.querySelector('#range-value_start');

var rangeValue = function(){
  var newValue = elem.value;
  var target = document.querySelector('.range-value_start');
  target.innerHTML = newValue;
  document.getElementById("bcStartAgeIntro").classList.remove("is-hidden");
  document.getElementById("bcStartAge").classList.remove("is-hidden");
  document.getElementById("bcStartAge").innerHTML = elem.value;
  document.getElementById("bcEndAgeIntro").classList.remove("is-hidden");
  document.getElementById("bcEndAge").classList.remove("is-hidden");
  document.getElementById("bcEndAge").innerHTML = elem2.value;
  if (elem.value > elem2.value) {
    elem2.value = parseInt (elem.value) + 1;
    document.querySelector('.range-value_end').innerHTML = parseInt (elem.value) + 1;
  }
}

elem.addEventListener("input", rangeValue);

var elem2 = document.querySelector('#range-value_end');

var rangeValue = function(){
  var newValue = elem2.value;
  var target = document.querySelector('.range-value_end');
  target.innerHTML = newValue;
  document.getElementById("bcStartAgeIntro").classList.remove("is-hidden");
  document.getElementById("bcStartAge").classList.remove("is-hidden");
  document.getElementById("bcStartAge").innerHTML = elem.value;
  document.getElementById("bcEndAgeIntro").classList.remove("is-hidden");
  document.getElementById("bcEndAge").classList.remove("is-hidden");
  document.getElementById("bcEndAge").innerHTML = elem2.value;
  if (elem.value > elem2.value) {
    elem.value = parseInt (elem2.value) - 1;
    document.querySelector('.range-value_start').innerHTML = elem.value;
  }
}

elem2.addEventListener("input", rangeValue);