// Get the period value from the radio buttons
function getPeriod() {
const periodRadios = document.querySelectorAll('input[name="period"]');

    for (let i = 0; i < periodRadios.length; i++) {
        periodRadios[i].addEventListener('change', function() {
            if (this.checked) {
                fetchHistoryFixing(this.value);
            }
        });

        if (periodRadios[i].checked) {
            fetchHistoryFixing(periodRadios[i].value);
        }
    }
}

let myChart;

// Fetch history fixing from API
function fetchHistoryFixing(period) {
    const ctx = document.getElementById('joubert-api-chart');
    const apiLink = joubertApi.api_link + 'history-fixing/' + period;

    fetch(apiLink)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (myChart) {
                myChart.destroy();
            }

            myChart = new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
}

getPeriod()


// Fetch metal price from API
function fetchMetalPrice() {
    const apiLink = joubertApi.api_link + 'real-time-commodity/' + joubertApi.metal; // URL of the API

    fetch(apiLink)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Process your data here
            const rtlElement = document.getElementById('joubert-api-rtl');
            rtlElement.children[0].innerText = data[0].metal;
            rtlElement.children[1].innerText = data[0].price + ' ' + data[0].currency;
            rtlElement.children[2].innerText = data[0].chp + ' %';
            if (data[0].chp > 0) {
                rtlElement.children[2].classList.add('joubert-api-ch-positive')
            } else {
                rtlElement.children[2].classList.add('joubert-api-ch-negative')
            }


        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
}

fetchMetalPrice();

// Set the interval to refresh the data
const frequencyApiCall = joubertApi.frequency_api_call;
setInterval(fetchMetalPrice, frequencyApiCall * 1000);
