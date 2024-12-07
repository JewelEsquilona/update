function updateDepartments() {
    const college = document.getElementById('College').value;
    const departmentSelect = document.getElementById('Department');
    const sectionSelect = document.getElementById('Section');

    departmentSelect.innerHTML = '<option value="">Select Department</option>';
    sectionSelect.innerHTML = '<option value="">Select Section</option>';

    if (college) {
        fetch(`get_departments.php?college=${college}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(department => {
                    const option = document.createElement('option');
                    option.value = department;
                    option.textContent = department;
                    departmentSelect.appendChild(option);
                });
                document.getElementById('DepartmentContainer').style.display = 'block';
            });
    } else {
        document.getElementById('DepartmentContainer').style.display = 'none';
    }
}

function updateSections() {
    const department = document.getElementById('Department').value;
    const sectionSelect = document.getElementById('Section');

    sectionSelect.innerHTML = '<option value="">Select Section</option>';

    if (department) {
        fetch(`get_sections.php?department=${department}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section;
                    option.textContent = section;
                    sectionSelect.appendChild(option);
                });
                document.getElementById('SectionContainer').style.display = 'block';
            });
    } else {
        document.getElementById('SectionContainer').style.display = 'none';
    }
}

function toggleEmploymentFields() {
    const employmentStatus = document.getElementById('Employment').value;

    // Hide all employment-related fields initially
    const fieldsToHide = [
        'EmploymentStatusContainer',
        'PresentOccupationContainer',
        'EmployerNameContainer',
        'EmployerAddressContainer',
        'YearsInEmployerContainer',
        'TypeOfEmployerContainer',
        'MajorLineOfBusinessContainer'
    ];
    fieldsToHide.forEach(field => {
        document.getElementById(field).style.display = 'none';
    });

    // Show fields only if "Employed" is selected
    if (employmentStatus === 'Employed') {
        fieldsToHide.forEach(field => {
            document.getElementById(field).style.display = 'block';
        });
    }
}
