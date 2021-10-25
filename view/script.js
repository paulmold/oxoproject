function createJobHTML(job) {
    return '<div className="job">' +
        '<h2>' + job.name + '</h2>' +
        '<p>' + job.description + '</p>' +
        '<table><tr>' +
        '<td><b>Expiration</b></td><td>' + job.expiration + '</td>' +
        '</tr><tr>' +
        '<td><b>Oppenings</b></td><td>' + job.opening + '</td>' +
        '</tr><tr>' +
        '<td><b>Company</b></td><td>' + job.company_name + '</td>' +
        '</tr><tr>' +
        '<td><b>Profession</b></td><td>' + job.profession_name + '</td>' +
        '</tr></table></div>';
}

function getJobs() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        document.getElementById("demo").innerHTML =
            this.responseText;
    }
    xhttp.open("GET", "/api/job");
    xhttp.send();
}