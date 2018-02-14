schoex.controller('semesterController', function (dataFactory, $rootScope, $scope) {
    $scope.semesters = {};
    $scope.views = {};
    $scope.views.list = true;
    $scope.form = {};
    $scope.academicYears = {};

    dataFactory.httpRequest('index.php/academic/listAll').then(function (data) {
        $scope.academicYears = data;
        showHideLoad(true);
    });

    $scope.selectYear = function () {
        dataFactory.httpRequest('index.php/semester/listAll/' + $scope.academicYear).then(function (data) {
            $scope.semesters = data;

        });
    };


    $scope.remove = function (item, index) {
        var confirmRemove = confirm($rootScope.phrase.sureRemove);
        if (confirmRemove === true) {
            showHideLoad();
            dataFactory.httpRequest('index.php/semester/delete/' + item.id, 'POST').then(function (data) {
                response = apiResponse(data, 'remove');
                $scope.semesters.splice(index, 1);
                showHideLoad(true);
            });
        }
    };

    $scope.edit = function (id) {
        showHideLoad();
        dataFactory.httpRequest('index.php/semester/' + id).then(function (data) {
            $scope.changeView('edit');
            $scope.form = data;
            showHideLoad(true);
        });
    };

    $scope.saveEdit = function () {
        showHideLoad();
        dataFactory.httpRequest('index.php/semester/' + $scope.form.id, 'POST', {}, $scope.form).then(function (data) {
            response = apiResponse(data, 'edit');
            if (response) {
                $scope.academicYear = $scope.form.academic_year_id;
                $scope.selectYear();
                $scope.changeView('list');

            }
            showHideLoad(true);
        });
    };

    $scope.saveAdd = function () {
        showHideLoad();

        dataFactory.httpRequest('index.php/semester', 'POST', {}, $scope.form).then(function (data) {
            var response = apiResponse(data, 'add');
            if (response) {
                $scope.academicYear = $scope.form.academic_year_id;
                $scope.changeView('list');
                $scope.selectYear();

            }
            showHideLoad(true);
        });
    };

    $scope.changeView = function (view) {
        if (view == "add" || view == "list" || view == "show") {
            $scope.form = {};
        }
        $scope.views.list = false;
        $scope.views.add = false;
        $scope.views.edit = false;
        $scope.views[view] = true;
    };
});

schoex.controller('quarterController', function (dataFactory, $rootScope, $scope) {
    $scope.semesters = {};
    $scope.academicYears = {};
    $scope.academicYear = {};
    $scope.selSemester = {};
    $scope.quarters = {};
    $scope.views = {};
    $scope.views.list = true;
    $scope.form = {};

    dataFactory.httpRequest('index.php/academic/listAll').then(function (data) {
        $scope.academicYears = data;
        showHideLoad(true);
    });

    $scope.selectYear = function () {
        dataFactory.httpRequest('index.php/semester/listAll/' + $scope.academicYear).then(function (data) {
            $scope.quarters = {};
            $scope.semseters = {};
            $scope.selSemester = {};
            $scope.semesters = data;

        });
    };
    $scope.selectSemester = function () {
        dataFactory.httpRequest('index.php/quarter/listAll/' + $scope.selSemester).then(function (data) {
            $scope.quarters = data;
        });
    };


    $scope.remove = function (item, index) {
        var confirmRemove = confirm($rootScope.phrase.sureRemove);
        if (confirmRemove == true) {
            showHideLoad();
            dataFactory.httpRequest('index.php/quarter/delete/' + item.id, 'POST').then(function (data) {
                response = apiResponse(data, 'remove');
                $scope.quarters.splice(index, 1);
                showHideLoad(true);
            });
        }
    }

    $scope.edit = function (id) {
        showHideLoad();
        dataFactory.httpRequest('index.php/quarter/' + id).then(function (data) {
            $scope.changeView('edit');
            $scope.form = data;
            showHideLoad(true);
        });
    };

    $scope.saveEdit = function () {
        showHideLoad();
        dataFactory.httpRequest('index.php/quarter/' + $scope.form.id, 'POST', {}, $scope.form).then(function (data) {
            response = apiResponse(data, 'edit');
            if (response) {

                $scope.changeView('list');
                $scope.selectYear();
                $scope.selSemester = response.semester;
                $scope.selectSemester();

            }
            showHideLoad(true);
        });
    };

    $scope.saveAdd = function () {
        showHideLoad();

        dataFactory.httpRequest('index.php/quarter', 'POST', {}, $scope.form).then(function (data) {
            var response = apiResponse(data, 'add');
            if (response) {

                $scope.changeView('list');
                $scope.selectYear();
                $scope.selSemester = response.semester;
                $scope.selectSemester();

            }
            showHideLoad(true);
        });
    };

    $scope.changeView = function (view) {
        if (view == "add" || view == "list" || view == "show") {
            $scope.form = {};
        }
        $scope.views.list = false;
        $scope.views.add = false;
        $scope.views.edit = false;
        $scope.views[view] = true;
    };
});

schoex.controller('weekController', function (dataFactory, $rootScope, $scope) {
    $scope.semesters = {};
    $scope.academicYears = {};
    $scope.academicYear = {};
    $scope.selSemester = {};
    $scope.selQuarter = {};
    $scope.quarters = {};
    $scope.weeks = {};
    $scope.views = {};
    $scope.views.list = true;
    $scope.form = {};

    dataFactory.httpRequest('index.php/academic/listAll').then(function (data) {
        $scope.academicYears = data;
        showHideLoad(true);
    });

    $scope.selectYear = function () {
        dataFactory.httpRequest('index.php/semester/listAll/' + $scope.academicYear).then(function (data) {
            $scope.weeks = {};
            $scope.quarters = {};
            $scope.semseters = {};
            $scope.selSemester = {};
            $scope.selQuarter = {};
            $scope.semesters = data;

        });
    };
    $scope.selectSemester = function () {
        dataFactory.httpRequest('index.php/quarter/listAll/' + $scope.selSemester).then(function (data) {
            $scope.quarters = data;
        });
    };

    $scope.selectQuarter = function () {
        dataFactory.httpRequest('index.php/week/listAll/' + $scope.selQuarter).then(function (data) {
            $scope.weeks = data;
        });
    };


    $scope.remove = function (item, index) {
        var confirmRemove = confirm($rootScope.phrase.sureRemove);
        if (confirmRemove == true) {
            showHideLoad();
            dataFactory.httpRequest('index.php/week/delete/' + item.id, 'POST').then(function (data) {
                response = apiResponse(data, 'remove');
                $scope.weeks.splice(index, 1);
                showHideLoad(true);
            });
        }
    }

    $scope.edit = function (id) {
        showHideLoad();
        dataFactory.httpRequest('index.php/week/' + id).then(function (data) {
            $scope.changeView('edit');
            $scope.form = data;
            showHideLoad(true);
        });
    }

    $scope.saveEdit = function () {
        showHideLoad();
        dataFactory.httpRequest('index.php/week/' + $scope.form.id, 'POST', {}, $scope.form).then(function (data) {
            response = apiResponse(data, 'edit');
            if (response) {

                $scope.changeView('list');
                $scope.selectYear();
                $scope.selectSemester();
                $scope.selQuarter = response.quarter;
                $scope.selectQuarter();

            }
            showHideLoad(true);
        });
    }

    $scope.saveAdd = function () {
        showHideLoad();

        dataFactory.httpRequest('index.php/week', 'POST', {}, $scope.form).then(function (data) {
            var response = apiResponse(data, 'add');
            if (response) {

                $scope.changeView('list');
                $scope.selectYear();
                $scope.selectSemester();
                $scope.selQuarter = response.quarter;
                $scope.selectQuarter();

            }
            showHideLoad(true);
        });
    };

    $scope.changeView = function (view) {
        if (view == "add" || view == "list" || view == "show") {
            $scope.form = {};
        }
        if (view == 'add') {
            $scope.form.quarter_id = $scope.selQuarter;
        }
        $scope.views.list = false;
        $scope.views.add = false;
        $scope.views.edit = false;
        $scope.views[view] = true;
    }
});

schoex.controller('divisionController', function (dataFactory, $rootScope, $scope) {
    $scope.divisions = {};
    $scope.views = {};
    $scope.views.list = true;
    $scope.form = {};
    $scope.refresh = function () {
        dataFactory.httpRequest('index.php/division/listAll').then(function (data) {
            $scope.divisions = data;
            showHideLoad(true);
        });
    };

    $scope.remove = function (item, index) {
        var confirmRemove = confirm($rootScope.phrase.sureRemove);
        if (confirmRemove == true) {
            showHideLoad();
            dataFactory.httpRequest('index.php/division/delete/' + item.id, 'POST').then(function (data) {
                response = apiResponse(data, 'remove');
                $scope.divisions.splice(index, 1);
                showHideLoad(true);
            });
        }
    };

    $scope.edit = function (id) {
        showHideLoad();
        dataFactory.httpRequest('index.php/division/' + id).then(function (data) {
            $scope.changeView('edit');
            $scope.form = data;
            showHideLoad(true);
        });
    };

    $scope.saveEdit = function () {
        showHideLoad();
        dataFactory.httpRequest('index.php/division/' + $scope.form.id, 'POST', {}, $scope.form).then(function (data) {
            response = apiResponse(data, 'edit');
            if (response) {
                $scope.refresh();
                $scope.changeView('list');
                showHideLoad(true);
            }
        });
    };

    $scope.saveAdd = function () {
        showHideLoad();

        dataFactory.httpRequest('index.php/division', 'POST', {}, $scope.form).then(function (data) {
            var response = apiResponse(data, 'add');
            if (response) {
                $scope.changeView('list');
                $scope.refresh();
            }
            showHideLoad(true);
        });
    };
    $scope.refresh();
    $scope.changeView = function (view) {
        if (view == "add" || view == "list" || view == "show") {
            $scope.form = {};
        }
        $scope.views.list = false;
        $scope.views.add = false;
        $scope.views.edit = false;
        $scope.views[view] = true;
    };
});

schoex.controller('buildingTypeController', function (dataFactory, $rootScope, $scope) {
    $scope.buildingTypes = {};
    $scope.views = {};
    $scope.views.list = true;
    $scope.form = {};

    function iformat(icon) {

        if (icon.element && icon.text !== "" && icon.element.value != undefined) {
            var originalOption = icon.element;
            return $('<span><i class="fa ' + originalOption.value + '"></i> ' + icon.text + '</span>');
        }
    }

    $('.select2').select2({
        width: "75%",
        templateSelection: iformat,
        templateResult: iformat,
        allowHtml: true
    });

    dataFactory.httpRequest('assets/js/Angular/font-awesome.json').then(function (data) {
        $scope.icons = data;
    });

    $scope.refresh = function () {
        dataFactory.httpRequest('index.php/buildingType/listAll').then(function (data) {
            $scope.buildingTypes = data;
            showHideLoad(true);
        });
    };

    $scope.remove = function (item, index) {
        var confirmRemove = confirm($rootScope.phrase.sureRemove);
        if (confirmRemove == true) {
            showHideLoad();
            dataFactory.httpRequest('index.php/buildingType/delete/' + item.id, 'POST').then(function (data) {
                response = apiResponse(data, 'remove');
                $scope.buildingTypes.splice(index, 1);
                showHideLoad(true);
            });
        }
    };

    $scope.edit = function (id) {
        showHideLoad();
        dataFactory.httpRequest('index.php/buildingType/' + id).then(function (data) {
            $scope.changeView('edit');
            $scope.form = data;
            showHideLoad(true);
        });
    };

    $scope.saveEdit = function () {
        showHideLoad();
        dataFactory.httpRequest('index.php/buildingType/' + $scope.form.id, 'POST', {}, $scope.form).then(function (data) {
            response = apiResponse(data, 'edit');
            if (response) {
                $scope.refresh();
                $scope.changeView('list');
                showHideLoad(true);
            }
        });
    };

    $scope.saveAdd = function () {
        showHideLoad();

        dataFactory.httpRequest('index.php/buildingType', 'POST', {}, $scope.form).then(function (data) {
            var response = apiResponse(data, 'add');
            if (response) {
                $scope.changeView('list');
                $scope.refresh();
            }
            showHideLoad(true);
        });
    };
    $scope.refresh();
    $scope.changeView = function (view) {
        if (view == "add" || view == "list" || view == "show") {
            $scope.form = {};
        }
        $scope.views.list = false;
        $scope.views.add = false;
        $scope.views.edit = false;
        $scope.views[view] = true;
    }
});

schoex.controller('buildingController', function (dataFactory, $rootScope, $scope) {
    $scope.buildings = [];
    $scope.buildingTypes = [];
    $scope.buildingParents = [];
    $scope.views = {};
    $scope.views.list = true;
    $scope.form = {};

    $scope.refresh = function () {
        dataFactory.httpRequest('index.php/building/listAll').then(function (data) {
            $scope.buildings = data;
            showHideLoad(true);
        });
        dataFactory.httpRequest('index.php/building/listParents').then(function (data) {
            $scope.buildingParents = data;
        });

        dataFactory.httpRequest('index.php/buildingType/listAll').then(function (data) {
            angular.forEach(data, function (item) {
                if (item.active == 1) {
                    $scope.buildingTypes.push(item);
                }
            });

        });
    };


    $scope.remove = function (item, index) {
        var confirmRemove = confirm($rootScope.phrase.sureRemove);
        if (confirmRemove == true) {
            showHideLoad();
            dataFactory.httpRequest('index.php/building/delete/' + item.id, 'POST').then(function (data) {
                response = apiResponse(data, 'remove');
                $scope.buildings.splice(index, 1);
                showHideLoad(true);
            });
        }
    };

    $scope.edit = function (id) {
        showHideLoad();
        dataFactory.httpRequest('index.php/building/' + id).then(function (data) {
            $scope.changeView('edit');
            $scope.form = data;
            for (i = 0; i < $scope.buildingParents.length; i++) {
                var item = $scope.buildingParents[i];
                if (item.id === id) {
                    $scope.buildingParents[i] = "";
                }
            }

            showHideLoad(true);
        });
    };

    $scope.saveEdit = function () {
        showHideLoad();
        dataFactory.httpRequest('index.php/building/' + $scope.form.id, 'POST', {}, $scope.form).then(function (data) {
            response = apiResponse(data, 'edit');
            if (response) {
                $scope.refresh();
                $scope.changeView('list');
                showHideLoad(true);
            }
        });
    };

    $scope.saveAdd = function () {
        showHideLoad();

        dataFactory.httpRequest('index.php/building', 'POST', {}, $scope.form).then(function (data) {
            var response = apiResponse(data, 'add');
            if (response) {
                $scope.changeView('list');
                $scope.refresh();
            }
            showHideLoad(true);
        });
    };
    $scope.refresh();
    $scope.changeView = function (view) {
        if (view == "add" || view == "list" || view == "show") {
            $scope.form = {};
        }
        $scope.views.list = false;
        $scope.views.add = false;
        $scope.views.edit = false;
        $scope.views[view] = true;
    }
});

schoex.controller('stageController', function (dataFactory, $rootScope, $scope) {
    $scope.stages = [];
    $scope.divisions = [];
    $scope.views = {};
    $scope.views.list = true;
    $scope.form = {};
    $scope.division = '';

    dataFactory.httpRequest('index.php/division/listAll/').then(function (data) {
        angular.forEach(data, function (item) {
            if (item.active == 1) {
                $scope.divisions.push(item);
            }
        });
    });

    $scope.selectDivision = function () {
        dataFactory.httpRequest('index.php/stage/listAll/' + $scope.division).then(function (data) {
            $scope.stages = data;

        });
    };

    $scope.remove = function (item, index) {
        var confirmRemove = confirm($rootScope.phrase.sureRemove);
        if (confirmRemove == true) {
            showHideLoad();
            dataFactory.httpRequest('index.php/stage/delete/' + item.id, 'POST').then(function (data) {
                response = apiResponse(data, 'remove');
                $scope.stages.splice(index, 1);
                showHideLoad(true);
            });
        }
    };

    $scope.edit = function (id) {
        showHideLoad();
        dataFactory.httpRequest('index.php/stage/' + id).then(function (data) {
            $scope.changeView('edit');
            $scope.form = data;
            showHideLoad(true);
        });
    };

    $scope.saveEdit = function () {
        showHideLoad();
        dataFactory.httpRequest('index.php/stage/' + $scope.form.id, 'POST', {}, $scope.form).then(function (data) {
            response = apiResponse(data, 'edit');
            if (response) {
                $scope.changeView('list');
                $scope.division=response.division_id;
                $scope.selectDivision();
                showHideLoad(true);
            }
        });
    };

    $scope.saveAdd = function () {
        showHideLoad();

        dataFactory.httpRequest('index.php/stage', 'POST', {}, $scope.form).then(function (data) {
            var response = apiResponse(data, 'add');
            if (response) {
                $scope.changeView('list');
                $scope.division=response.division_id;
                $scope.selectDivision();
            }
            showHideLoad(true);
        });
    };

    $scope.changeView = function (view) {
        if (view == "add" || view == "list" || view == "show") {
            $scope.form = {};
        }
        if (view === 'add') {
            $scope.form.division_id = $scope.division;
        }
        $scope.views.list = false;
        $scope.views.add = false;
        $scope.views.edit = false;
        $scope.views[view] = true;
    };
    showHideLoad(true);
});

schoex.controller('classAgeController', function (dataFactory, $rootScope, $scope) {
    $scope.classAges = {};
    $scope.views = {};
    $scope.views.list = true;
    $scope.form = {};
    $scope.academicYears = {};

    dataFactory.httpRequest('index.php/academic/listAll').then(function (data) {
        $scope.academicYears = data;
        showHideLoad(true);
    });

    $scope.selectYear = function () {
        dataFactory.httpRequest('index.php/classAge/listAll/' + $scope.academicYear).then(function (data) {
            $scope.classAges = data;

        });
    };


    $scope.remove = function (item, index) {
        var confirmRemove = confirm($rootScope.phrase.sureRemove);
        if (confirmRemove === true) {
            showHideLoad();
            dataFactory.httpRequest('index.php/classAge/delete/' + item.id, 'POST').then(function (data) {
                response = apiResponse(data, 'remove');
                $scope.classAges.splice(index, 1);
                showHideLoad(true);
            });
        }
    };

    $scope.edit = function (id) {
        showHideLoad();
        dataFactory.httpRequest('index.php/classAge/' + id).then(function (data) {
            $scope.changeView('edit');
            $scope.form = data;
            showHideLoad(true);
        });
    };

    $scope.saveEdit = function () {
        showHideLoad();
        dataFactory.httpRequest('index.php/classAge/' + $scope.form.id, 'POST', {}, $scope.form).then(function (data) {
            response = apiResponse(data, 'edit');
            if (response) {
                $scope.academicYear = $scope.form.academic_year_id;
                $scope.selectYear();
                $scope.changeView('list');

            }
            showHideLoad(true);
        });
    };

    $scope.saveAdd = function () {
        showHideLoad();

        dataFactory.httpRequest('index.php/classAge', 'POST', {}, $scope.form).then(function (data) {
            var response = apiResponse(data, 'add');
            if (response) {
                $scope.academicYear = $scope.form.academic_year_id;
                $scope.changeView('list');
                $scope.selectYear();

            }
            showHideLoad(true);
        });
    };

    $scope.changeView = function (view) {
        if (view === "add" || view === "list" || view === "show") {
            $scope.form = {};
        }
        $scope.views.list = false;
        $scope.views.add = false;
        $scope.views.edit = false;
        $scope.views[view] = true;
    };
});