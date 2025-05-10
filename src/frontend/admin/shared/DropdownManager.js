class DropdownManager {
    static async populateDropdown(selectElement, dataSource, valueField, textField) {
        try {
            const data = dataSource
            
            if (Array.isArray(data)) {
                data.forEach(item => {
                    const option = document.createElement('sl-option');
                    option.value = item[valueField];
                    option.textContent = item[textField] || item[valueField];
                    selectElement.appendChild(option);
                });
            } else if (typeof data === 'object') {
                Object.entries(data).forEach(([key, value]) => {
                    const option = document.createElement('sl-option');
                    option.value = value;
                    option.textContent = value;
                    selectElement.appendChild(option);
                });
            }
        } catch (error) {
            console.error(`Failed to populate dropdown ${selectElement.id}:`, error);
        }
    }

    static setupHiddenFieldBinding(selectElementId, hiddenFieldId) {
        const select = document.getElementById(selectElementId);
        const hidden = document.getElementById(hiddenFieldId);
        
        if (select && hidden) {
            hidden.value = select.value;
            select.addEventListener('sl-change', (event) => {
                hidden.value = event.target.value;
            });
        }
    }
}

export default DropdownManager;