async function loadTable(tableName) {
    try {
        const response = await fetch('getData.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ tableName: tableName })
        });

        const data = await response.json();

        if (data.length === 0) {
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '<tr><td colspan="100%">Нет данных</td></tr>';
            return;
        }

        if (tableName === 'camera') {
            const tableHeader = document.getElementById('table-header');
            const tableBody = document.getElementById('table-body');
            
            // Определяем колонки для таблицы camera
            const columns = [
                'ID',
                'Название',
                'Размер',
                'Цвет',
                'Стоимость',
                'Материал',
                'Страна',
                'Действия'
            ];
            
            // Заголовки таблицы
            tableHeader.innerHTML = columns.map(col => 
                col === 'Действия' ? `<th>${col}</th>` : `<th onclick="sortTable(this)">${col}</th>`
            ).join('');

            // Генерируем строки с данными
            tableBody.innerHTML = data.map(row => {
                return `
                    <tr>
                        <td>${row.id || ''}</td>
                        <td>${row.tittle || ''}</td>
                        <td>${row.size || ''}</td>
                        <td>
                            <span class="color-preview" style="background-color: ${row.color || '#fff'};"></span>
                            ${row.color || ''}
                        </td>
                        <td>${row.cost ? `${row.cost} ₽` : ''}</td>
                        <td>${row.material_name || 'Не указано'}</td>
                        <td>${row.country_name || 'Не указано'}</td>
                        <td><button onclick="deleteData('${row.id}')" class="btn btn-danger btn-sm">Удалить</button></td>
                    </tr>
                `;
            }).join('');
        }
        else if (tableName === 'material') {
            console.log(data);
            const material_id = document.getElementById('material_id');

            const uniqueMaterials = [];
            const seenIds = new Set();

            data.forEach(row => {
                if (!seenIds.has(row.id)) {
                    seenIds.add(row.id);
                    uniqueMaterials.push({
                        id: row.id,
                        name: row.material
                    });
                }
            });

            material_id.innerHTML = uniqueMaterials.map(material =>
                `<option value="${material.id}">${material.name}</option>`
            ).join('');

        }
        else if (tableName === 'countries') {
            console.log(data);
            const country_id = document.getElementById('country_id');

            const uniqueCountries = [];
            const seenIds = new Set();

            data.forEach(row => {
                if (!seenIds.has(row.id)) {
                    seenIds.add(row.id);
                    uniqueCountries.push({
                        id: row.id,
                        name: row.name
                    });
                }
            });

            country_id.innerHTML = uniqueCountries.map(country =>
                `<option value="${country.id}">${country.name}</option>`
            ).join('');
        }
        else {
            console.error('Unknown table:', tableName);
        }

    } catch (error) {
        console.error('Ошибка загрузки данных:', error);
    }
}
async function addData() {
  const data = {
    tittle: document.getElementById('tittle').value,
    size: document.getElementById('size').value,
    color: document.getElementById('color').value,
    cost: document.getElementById('cost').value,
    material_id: document.getElementById('material_id').value,
    country_id: document.getElementById('country_id').value
    }

    try {
        const response = await fetch('postData.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            alert('Данные успешно добавлены!');
            document.getElementById('addCamera').reset();
            loadTable('camera');
        } else {
            alert('Ошибка: ' + result.error);
        }
    } catch (error) {
        console.error('Ошибка:', error);

    }
}

async function deleteData(id) {
    // Подтверждение удаления
    if (!confirm(`Вы уверены, что хотите удалить запись с ID: ${id}?`)) {
        return;
    }

    try {
        // Вариант 1: Отправка DELETE запроса с JSON
        const response = await fetch('delete.php', {
            method: 'DELETE', // или 'POST' если используете другой метод
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id })
        });

        const result = await response.json();

        if (result.success) {
            alert('Запись успешно удалена!');
            // Обновляем таблицу после удаления
            loadTable('camera');
        } else {
            alert('Ошибка при удалении: ' + result.error);
        }
    } catch (error) {
        console.error('Ошибка:', error);
        alert('Произошла ошибка при удалении');
    }
}

function sortTable(header) {
    const table = document.getElementById("data-table");
    const tbody = table.tBodies[0];

    const rows = Array.from(tbody.rows);
    const columnIndex = header.cellIndex;

    // Получаем текущий порядок сортировки из data-атрибута
    const currentSortOrder = tbody.dataset.sortOrder;
    const currentSortColumn = tbody.dataset.sortColumn;

    // Определяем направление сортировки
    let isAscending;
    if (currentSortColumn === columnIndex.toString()) {
        isAscending = currentSortOrder === "desc";
    } else {
        isAscending = true;
    }

    // Сортируем строки
    rows.sort((a, b) => {
        const aText = a.cells[columnIndex].textContent.trim();
        const bText = b.cells[columnIndex].textContent.trim();

        // Пробуем преобразовать текст в числа
        const aValue = parseFloat(aText);
        const bValue = parseFloat(bText);

        // Проверяем, являются ли оба значения числами
        const aIsNumber = !isNaN(aValue);
        const bIsNumber = !isNaN(bValue);

        if (aIsNumber && bIsNumber) {
            // Оба значения - числа
            return isAscending ? aValue - bValue : bValue - aValue;
        } else if (aIsNumber) {
            // Только a - число
            return isAscending ? -1 : 1; // Число идет перед строкой
        } else if (bIsNumber) {
            // Только b - число
            return isAscending ? 1 : -1; // Строка идет перед числом
        } else {
            // Оба значения - строки
            return isAscending ? aText.localeCompare(bText) : bText.localeCompare(aText);
        }
    });

    // Удаляем старые строки и добавляем отсортированные
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    tbody.append(...rows);

    // Сохраняем состояние сортировки
    tbody.dataset.sortOrder = isAscending ? "asc" : "desc";
    tbody.dataset.sortColumn = columnIndex;

    // Добавляем визуальные индикаторы сортировки (опционально)
    updateSortIndicators(header, isAscending);
}

// Функция для обновления визуальных индикаторов сортировки
function updateSortIndicators(activeHeader, isAscending) {
    // Убираем индикаторы со всех заголовков
    const allHeaders = document.querySelectorAll('#table-header th');
    allHeaders.forEach(header => {
        header.classList.remove('sorted-asc', 'sorted-desc');
    });

    // Добавляем индикатор на активный заголовок
    activeHeader.classList.add(isAscending ? 'sorted-asc' : 'sorted-desc');
}

document.getElementById('addCamera').addEventListener('submit', function(event) {
    event.preventDefault();
    addData();
});

// document.getElementById('deleteData').addEventListener('onclick', function(evenv){
//   event.preventDefault();
//   deleteData();
// })

loadTable('camera').then(data => {
    console.log(data);
});

loadTable('material').then(data => {
    console.log(data);
});

loadTable('countries').then(data =>{
  console.log(data);
});

function xlsx(){
  const exportBtn = document.getElementById('btn-export');
  const table = document.getElementById('data-table');
  exportBtn.addEventListener('click', () => {
  /* Create worksheet from HTML DOM TABLE */
  const wb = XLSX.utils.table_to_book(table, {sheet: 'sheet-1'});

  /* Export to file (start a download) */
  XLSX.writeFile(wb, 'MyTable.xlsx');
  });
}
xlsx();
// Функция для работы с выбором цвета
function initColorPicker() {
    const colorInput = document.getElementById('color');
    const colorPickerBtn = document.getElementById('color-picker-btn');
    const colorInputNative = document.getElementById('color-input');
    const colorPalette = document.getElementById('color-palette');
    const colorOptions = document.querySelectorAll('.color-option');
    
    // Показать/скрыть палитру
    colorPickerBtn.addEventListener('click', function() {
        colorPalette.style.display = colorPalette.style.display === 'none' ? 'block' : 'none';
    });
    
    // Выбор цвета из палитры
    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            const selectedColor = this.getAttribute('data-color');
            
            // Устанавливаем значение в поле ввода
            colorInput.value = selectedColor;
            colorInputNative.value = selectedColor;
            
            // Обновляем визуальное отображение
            colorOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            
            // Скрываем палитру после выбора
            colorPalette.style.display = 'none';
            
            // Изменяем цвет кнопки выбора
            colorPickerBtn.style.backgroundColor = selectedColor;
            colorPickerBtn.style.color = getContrastColor(selectedColor);
        });
    });
    
    // Выбор цвета через нативный input type="color"
    colorInputNative.addEventListener('input', function() {
        colorInput.value = this.value;
        colorPickerBtn.style.backgroundColor = this.value;
        colorPickerBtn.style.color = getContrastColor(this.value);
        
        // Снимаем выделение с предустановленных цветов
        colorOptions.forEach(opt => opt.classList.remove('selected'));
    });
    
    // Функция для определения контрастного цвета текста
    function getContrastColor(hexColor) {
        // Удаляем символ # если есть
        hexColor = hexColor.replace('#', '');
        
        // Преобразуем HEX в RGB
        const r = parseInt(hexColor.substr(0, 2), 16);
        const g = parseInt(hexColor.substr(2, 2), 16);
        const b = parseInt(hexColor.substr(4, 2), 16);
        
        // Вычисляем яркость по формуле
        const brightness = (r * 299 + g * 587 + b * 114) / 1000;
        
        // Возвращаем черный для светлых цветов, белый для темных
        return brightness > 128 ? '#000000' : '#ffffff';
    }
    
    // Закрытие палитры при клике вне её
    document.addEventListener('click', function(event) {
        if (!event.target.closest('#color-picker-btn') && 
            !event.target.closest('#color-palette') &&
            !event.target.closest('#color-input')) {
            colorPalette.style.display = 'none';
        }
    });
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    initColorPicker();
    
    // Обработка отправки формы
    document.getElementById('addCamera').addEventListener('submit', function(e) {
        // Здесь можно добавить валидацию цвета
        const colorValue = document.getElementById('color').value;
        
        if (colorValue && !/^#[0-9A-F]{6}$/i.test(colorValue)) {
            alert('Пожалуйста, выберите корректный цвет в формате HEX (#RRGGBB)');
            e.preventDefault();
            return;
        }
    });
});
initColorPicker();
