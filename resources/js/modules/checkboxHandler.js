
document.addEventListener('DOMContentLoaded', function () {
    
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

        childCheckboxes.forEach(child => {
            if (!child.checked) {
                allChecked = false;
            }
        });
        parentCheckbox.checked = allChecked;
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
