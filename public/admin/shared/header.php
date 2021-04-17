<?php
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    after_successful_logout(); //security checks
    header('location:../');
}
ob_start();
$account_types = [2, 3, 4];
pageRestrict($account_types, "../");
confirm_user_logged_in("../");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="Online QR Code Attendance Tracking Sytem Project in Software Design 2020-2021 URS">
    <meta name="keywords" content="QRDANCE, URS, University of Rizal System, Software Design, Attendance ">
    <meta name="author" content="SD Team">
    <title>QRDANCE</title>
    <!-- MDB icon -->
    <link rel="icon" href="./../node_modules/mdbootstrap/img/logo/favicon.ico" type="image/x-icon" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
    <!-- MDB -->
    <link rel="apple-touch-icon" sizes="180x180" href="./../node_modules/mdbootstrap/img/logo/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./../node_modules/mdbootstrap/img/logo/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./../node_modules/mdbootstrap/img/logo/favicon-16x16.png">
    <link rel="manifest" href="./../node_modules/mdbootstrap/img/logo/site.webmanifest">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./../node_modules/mdbootstrap/css/mdb.min.css" />
    <link rel="stylesheet" href="./../library/Croppie/croppie.css" />
    <link rel="stylesheet" href="./../library/dropify/dist/css/dropify.css" />
    <link rel="stylesheet" href="./../node_modules/mdbootstrap/css/styles.css" />
    <link rel="stylesheet" href="./../node_modules/mdbootstrap/DataTables/datatables.min.css">

    <script type="text/javascript">
        let tr, data, img, n;

        function query(view = "list", component = "staffs", elementID = "result") {
            //get the local and update form
            const form = $('#formAdd')[0];
            const formData = new FormData(form);
            $.ajax({
                url: `./components/${component}Component.php`,
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: (response) => {
                    $('.message').html(response);
                    getDataByView(view, component, elementID);
                }
            });
        }

        function getDataByView(view = "list", data = "staffs", elementID = "result") {
            var elExist = document.getElementById(elementID);
            if (elExist) {

                $.ajax({
                    url: `./data/${data}.php`,
                    type: 'POST',
                    data: {
                        'view': view
                    },
                    success: (response) => {
                        $(`#${elementID}`).html(response);
                    }
                });
            }

        }

        function dropifyReset() {
            $('.dropify-wrapper').removeClass('has-preview');
            $('.dropify-preview').css('display', 'none');
            const dropifyRender = document.querySelectorAll('.dropify-render')[0];
            dropifyRender.innerHTML = '';
        }

        function HideUpdateShowCreate(updateID = "update", createID = "create") {
            $(`#${updateID}`).hide();
            $(`#${createID}`).show();
        }

        function HideCreateShowUpdate(updateID = "update", createID = "create") {
            $(`#${createID}`).hide();
            $(`#${updateID}`).show();
        }

        function del(id, el, view = "list", page = "staffs") {
            let p;
            if (view == "list") {
                tr = el.parentElement.parentElement
                data = JSON.parse(tr.lastChild.previousSibling.value);

                if (page == 'staffs' || page == "students") {
                    img = tr.children[1].children[0].currentSrc;
                    n = img.indexOf("/st");
                    img = img.substr(n, img.length);
                }

            } else {
                data = JSON.parse(document.getElementById(id).value);
            }
            $('#dataDelete').html('');
            if (page == "staffs" || page == 'students') {

                const image = `<img  class="profileImg" style="height:9rem;" src="../node_modules/mdbootstrap/img${img}">`;
                p = `<p class=" text-center font-weight-bold" id="name">${data.fname} ${data.mname} ${data.lname}</p>`;
                $('#dataDelete').append(p);
                $('#dataDelete').append(image);
            } else if (page == "year") {
                p = `<p class="text-center font-weight-bold" id="name"> Year: ${data.year}</p>`;
                $('#dataDelete').append(p);
            } else if (page == "sections") {
                p = `<p class="text-center font-weight-bold" id="name"> Section: ${data.section}</p>`;
                $('#dataDelete').append(p);
            } else if (page == "courses") {
                p = `<p class="text-center font-weight-bold" id="name"> Course: ${data.course}</p>`;
                $('#dataDelete').append(p);
            } else if (page == "subjects") {
                p = `<p class="text-center font-weight-bold" id="name"> Subject: ${data.name_of_subject}</p>`;
                $('#dataDelete').append(p);
            } else if (page == "notifications") {
                p = `<p class="text-center font-weight-bold" id="name"> Message: ${data.message}</p>`;
                $('#dataDelete').append(p);
            }

            document.querySelector('#x').value = id;
        }

        function edit(id, el, view = "list", page = "staffs") {
            const input = document.querySelectorAll('.fields');
            let fields = [];
            if (view == "list") {
                tr = el.parentElement.parentElement
                data = JSON.parse(tr.lastChild.previousSibling.value);
                if (page == 'staffs' || page == "students") {
                    img = tr.children[1].children[0].currentSrc;
                    n = img.indexOf("/st");
                    img = img.substr(n, img.length);
                }
            } else {
                data = JSON.parse(document.getElementById(id).value);
            }

            if (Object.keys(data).length == 0 && data.id !== null) {
                return 0;
            }
            for (i in data) { //extract json object and save to array
                fields.push(data[i]);
            }
            document.querySelector('#s').value = data.id;
            console.log(`edit ${page} triggered`);

            HideCreateShowUpdate();
            if (page == "staffs" || page == 'students') {
                let imgPath = `../node_modules/mdbootstrap/img${img}`;
                //get the img src of staffs/students and add to the dropify preview
                const image = `<img class="imgPrev" src="${imgPath}">`;
                $('.dropify-wrapper').addClass("has-preview");
                $('.dropify-preview').css('display', 'block');
                //remove first if there's an existing img;
                const dropifyRender = document.querySelectorAll('.dropify-render')[0];
                dropifyRender.innerHTML = '';
                $('.dropify-render').append(image);
            }
            for (var i = 0; i < input.length; i++) {

                if (page == "students" && i >= 3) {
                    const options = input[i].children;
                    for (var j = 0; j < options.length; j++) {
                        if (options[j].value == fields[i + 1]) {
                            var attr = document.createAttribute("selected");
                            options[j].setAttributeNode(attr);
                            break;
                        }
                    }
                    continue;
                }
                if (page == "staffs" && i == 5) {
                    const options = input[5].children;
                    for (var j = 0; j < options.length; j++) {
                        if (options[j].value == fields[i + 1]) {
                            var attr = document.createAttribute("selected");
                            options[j].setAttributeNode(attr);
                            break
                        }
                    }
                    break;
                }
                if (page == "notifications" && i == 1) {
                    let radioBtn = document.getElementsByClassName('radioBtn');
                    for (var j = 0; j < radioBtn.length; j++) {
                        if (radioBtn[j].value == fields[3]) {
                            var attr = document.createAttribute("checked");
                            radioBtn[j].setAttributeNode(attr);
                            continue;
                        }
                    }
                }
                if (page == 'accounts' && i == 2) {
                    let radioBtn = document.getElementsByClassName('radioBtn');
                    for (var j = 0; j < radioBtn.length; j++) {
                        if (radioBtn[j].value == fields[4]) {
                            var attr = document.createAttribute("checked");
                            radioBtn[j].setAttributeNode(attr);
                            continue;
                        }
                    }
                }
                //add the active class on label so it doesn't mess with the data
                let label = input[i].nextElementSibling;
                label.classList.add("active");
                input[i].value = fields[i + 1];
            }
        }

        function addData(page = "staffs") {
            const form = $('#formAdd')[0];
            const formData = new FormData(form);
            const s = document.querySelector('#s');
            const input = document.querySelectorAll('.fields');
            let length = input.length;
            form.reset();
            if (page == 'staffs' ||
                page == "students") {
                length = 3;
                dropifyReset();
            }
            for (var i = 0; i < length; i++) {
                let label = input[i].nextElementSibling;
                label.classList.remove("active");
            }
            HideUpdateShowCreate();
            s.value = -1;
        }

        function createData(page = "staffs") {
            console.log(` create ${page} triggered`);
            if (page == 'staffs') {
                query();
            } else if (page == 'year') {
                query('list', 'year', 'yearResult');
            } else if (page == 'sections') {
                query('list', 'sections', 'sectionResult');
            } else if (page == "courses") {
                query('list', 'courses', 'courseResult');
            } else if (page == "subjects") {
                query('list', 'subjects', 'subjectResult');
            } else if (page == "students") {
                query('list', 'students', 'studentResult');
            } else if (page == "notifications") {
                query('list', 'notifications', 'notificationResult');
            }
        }

        function updateData(page = "staffs") {
            console.log(` update ${page} triggered`);
            const s = document.querySelector('#s').value;
            if (s > 0) {
                if (page == 'staffs') {
                    query();
                } else if (page == 'year') {
                    query('list', 'year', 'yearResult');
                } else if (page == 'sections') {
                    query('list', 'sections', 'sectionResult');
                } else if (page == "courses") {
                    query('list', 'courses', 'courseResult');
                } else if (page == "subjects") {
                    query('list', 'subjects', 'subjectResult');
                } else if (page == "students") {
                    query('list', 'students', 'studentResult');
                } else if (page == 'accounts') {
                    query('list', 'accounts', 'accountResult');
                } else if (page == 'notifications') {
                    query('list', 'notifications', 'notificationResult');
                } else if (page == 'settings') {
                    const form = $('#formAdd')[0];
                    const formData = new FormData(form);
                    $.ajax({
                        url: `./components/${page}Component.php`,
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: (response) => {
                            $('.message').html(response);
                        }
                    });
                } else if (page == 'appointments') {
                    query('list', 'appointments', 'appointmentResult');

                }
                return 0;
            }
            let response = `<div class="alert alert-warning">ID:${s} Invalid, Please Select First the Data that you want to update</div>`;
            $('.message').html(response);
        }

        function deleteData(page = "staffs", id = 0) {
            if (page == "professorSubjectList") {
                id = id.split(" ");
                $.ajax({
                    url: `./components/${page}Component.php`,
                    type: 'POST',
                    data: {
                        "deleteProfSubList": id
                    },
                    success: (response) => {
                        $('.message').html(response);
                        getDataByView('grid', 'professorSubjectList', 'pResult');
                    }
                });
                return 0;
            }
            if (page == "enrolledSubjects") {
                id = id.split(" ");
                $.ajax({
                    url: `./components/${page}Component.php`,
                    type: 'POST',
                    data: {
                        "deleteEnrolledSubject": id
                    },
                    success: (response) => {
                        $('.message').html(response);
                        getDataByView('grid', 'enrolledSubjects', 'enrolledSubjectResult');
                    }
                });
                return 0;
            }
            const x = document.querySelector('#x').value;
            const name = document.querySelector('#name').innerText;
            if (page == "staffs") {
                $.ajax({
                    url: `./components/${page}Component.php`,
                    type: 'POST',
                    data: {
                        "delete": x,
                        "name": name
                    },
                    success: (response) => {
                        $('.message').html(response);
                        getDataByView();
                    }
                });
            } else if (page == "year") {

                $.ajax({
                    url: `./components/${page}Component.php`,
                    type: 'POST',
                    data: {
                        "deleteYear": x,
                        "name": name
                    },
                    success: (response) => {
                        $('.message').html(response);
                        getDataByView('list', 'year', 'yearResult');
                    }
                });
            } else if (page == "sections") {
                $.ajax({
                    url: `./components/${page}Component.php`,
                    type: 'POST',
                    data: {
                        "deleteSection": x,
                        "name": name
                    },
                    success: (response) => {
                        $('.message').html(response);
                        getDataByView('list', 'sections', 'sectionResult');
                    }
                });
            } else if (page == "courses") {
                $.ajax({
                    url: `./components/${page}Component.php`,
                    type: 'POST',
                    data: {
                        "deleteCourse": x,
                        "name": name
                    },
                    success: (response) => {
                        $('.message').html(response);
                        getDataByView('list', 'courses', 'courseResult');
                    }
                });
            } else if (page == "subjects") {
                $.ajax({
                    url: `./components/${page}Component.php`,
                    type: 'POST',
                    data: {
                        "deleteSubject": x,
                        "name": name
                    },
                    success: (response) => {
                        $('.message').html(response);
                        getDataByView('list', 'subjects', 'subjectResult');
                    }
                });
            } else if (page == "students") {
                $.ajax({
                    url: `./components/${page}Component.php`,
                    type: 'POST',
                    data: {
                        "deleteStudent": x,
                        "name": name
                    },
                    success: (response) => {
                        $('.message').html(response);
                        getDataByView('list', 'students', 'studentResult');
                    }
                });
            } else if (page == "notifications") {
                $.ajax({
                    url: `./components/${page}Component.php`,
                    type: 'POST',
                    data: {
                        "deleteMessage": x,
                        "name": name
                    },
                    success: (response) => {
                        $('.message').html(response);
                        getDataByView('list', 'notifications', 'notificationResult');
                    }
                });
            }
            console.log(` delete ${page} triggered`);
        }

        function getData(page = "subjectAttendance") {
            if (page = "subjectAttendance") {
                const form = $('#formAdd')[0];
                const formData = new FormData(form);
                $.ajax({
                    url: `./data/subjectAttendance.php`,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: (response) => {
                        $('#subjectAttendanceResult').html(response);
                    }
                });
            }
            if (page = "viewReport.php") {
                const form = $('#formAdd')[0];
                const formData = new FormData(form);
                $.ajax({
                    url: `./data/report.php`,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: (response) => {
                        $('#viewReportResult').html(response);
                    }
                });

            }
        }

        function selected(el, page = "professorSubjectList") {
            let card = el.parentElement.parentElement.parentElement;
            let checkbox = el.children[0],
                name;
            if (page == 'professorSubjectList') {
                name = 'psl[]';
            } else if (page == 'enrolledSubjects') {
                name = 'eS[]';
            }
            if (!checkbox.hasAttribute('checked')) {
                card.setAttribute('style', 'border:1px solid cyan');
                let attr = document.createAttribute('checked');
                checkbox.setAttributeNode(attr);
                checkbox.setAttribute("name", name);
                checkbox.setAttribute("class", "y");
            } else {
                card.removeAttribute('style');
                checkbox.removeAttribute('checked');
                checkbox.removeAttribute('class');
                checkbox.removeAttribute('name');
            }

        }

        function saveToSub(el, page = "professorSubjectList") {
            const id = el.value;
            document.getElementById('sub').value = id;
            const form = $('#subjectList')[0];
            const formData = new FormData(form);

            $.ajax({
                url: `./components/${page}Component.php`,
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: (response) => {
                    $('.message').html(response);
                    if (page == "professorSubjectList") {
                        getDataByView('grid', 'professorSubjectList', 'pResult');
                    } else if (page == "enrolledSubjects") {
                        getDataByView('grid', 'enrolledSubjects', 'enrolledSubjectResult');

                    }
                }
            });
        }

        function accordion(el) {
            el.classList.toggle("active");
            let panel = el.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        }

        function sendEmail(id) {
            console.log('sendEmail');
            $.ajax({
                url: `./components/sendMailComponent.php`,
                type: 'POST',
                data: {
                    email: id
                },
                success: (response) => {
                    $('.message').html(response);
                }
            });
        }
    </script>
</head>