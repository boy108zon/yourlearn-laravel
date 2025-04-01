
document.addEventListener('DOMContentLoaded', function () {

    let totalChildren = 0;

    function handleParentCheckboxChange(parentId, childClass) {
        const parentCheckbox = document.getElementById(parentId);
        const childCheckboxes = document.querySelectorAll(`.${childClass}`);

        childCheckboxes.forEach(child => {
            child.checked = parentCheckbox.checked;
        });
    }

    function checkParentCheckbox(childClass, parentId) {
        const childCheckboxes = document.querySelectorAll(`.${childClass}`);
        const parentCheckbox = document.getElementById(parentId);
        let allChecked = true;

        totalChildren = 0;
        childCheckboxes.forEach(child => {
            if (!child.checked) {
                allChecked = false;
            }
            if (child.checked) {
                totalChildren++;
            }
        });

        if (totalChildren === 0) {
            parentCheckbox.checked = false;
        } else {
            parentCheckbox.checked = true
        }
       
    }

    document.querySelectorAll('.parent-checkbox').forEach(parent => {
        parent.addEventListener('change', function () {
            const parentId = parent.id;
            const childClass = parent.dataset.childClass;
            handleParentCheckboxChange(parentId, childClass);
        });
    });

    document.querySelectorAll('.child-checkbox').forEach(child => {
        child.addEventListener('change', function () {
            const parentId = child.dataset.parentId;
            const childClass = child.dataset.childClass;
            checkParentCheckbox(childClass, parentId);
        });
    });
});
