document.addEventListener('DOMContentLoaded',function() {

    const dataSelect = document.getElementsById('date');
    const classSelect = document.getElementById('class');
    const dataHolder = document.getElementById('data-holder');


    const dates = JSON.parse(dataHolder.dataset.dates);
    const defaultClasses = JSON.parse(dataHolder.dataset.defaultClasses);


    dates.forEach(function(date)) {

    const option = document.createElement('option');
    option.value = date.DayOfWeek;
    option.textContent = $(date.date) - $(date.DayOfWeek);
    dataSelect.appendChild(option);
    });

});
