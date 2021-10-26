var URL = "/api/job";
var SORTBY = null;
var COMPANY = null;
var PROFESSION = null;

function createJobHTML(job) {
    return '<div class="job">' +
        '<h2>' + job.name + '</h2>' +
        '<p>' + job.description + '</p>' +
        '<table><tr>' +
        '<td><b>Expiration</b></td><td>' + job.expiration + '</td>' +
        '</tr><tr>' +
        '<td><b>Oppenings</b></td><td>' + job.openings + '</td>' +
        '</tr><tr>' +
        '<td><b>Company</b></td><td>' + job.company + '</td>' +
        '</tr><tr>' +
        '<td><b>Profession</b></td><td>' + job.profession + '</td>' +
        '</tr></table></div>';
}

function createSelectHTML(company) {
    return '<option value="' + company.id + '">' + company.name + '</option>';
}

function getJobs() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        let jobs = JSON.parse(this.response);
        let html = "";
        jobs.forEach(function (job) {
            html += createJobHTML(job);

        });
        document.getElementById('jobs').innerHTML = html;
    }
    let getJobsUrl = URL;
    let add = "?";
    if (SORTBY) {
        getJobsUrl += add + "sortBy=" + SORTBY;
        add = "&";
    }
    if (COMPANY) {
        getJobsUrl += add + "company=" + COMPANY;
        add = "&";
    }
    if (PROFESSION) {
        getJobsUrl += add + "profession=" + PROFESSION;
        add = "&";
    }
    xhttp.open("GET", getJobsUrl);
    xhttp.send();
}

function sort() {
    SORTBY = this.value;
    getJobs();
}

function filterCompany() {
    COMPANY = this.value;
    getJobs();
}

function filterProfession() {
    PROFESSION = this.value;
    getJobs();
}

function getCompanies() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        let jobs = JSON.parse(this.response);
        let html = "<option selected value>-- none --</option>";
        jobs.forEach(function (company) {
            html += createSelectHTML(company);

        });
        document.getElementById('filterCompany').innerHTML = html;
    }
    xhttp.open("GET", "/api/company");
    xhttp.send();
}

function getProfessions() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        let jobs = JSON.parse(this.response);
        let html = "<option selected value>-- none --</option>";
        jobs.forEach(function (profession) {
            html += createSelectHTML(profession);

        });
        document.getElementById('filterProfession').innerHTML = html;
    }
    xhttp.open("GET", "/api/profession");
    xhttp.send();
}

getJobs();
getCompanies();
getProfessions();
document.getElementById('sortBy').addEventListener("change", sort);
document.getElementById('filterCompany').addEventListener("change", filterCompany);
document.getElementById('filterProfession').addEventListener("change", filterProfession);