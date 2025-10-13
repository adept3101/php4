async function loadTable() {
  try {
    const response = await fetch('getData.php');
    const data = await response.json();

    const tableHeader = document.getElementById('table-header');
    const tableBody = document.getElementById('table-body');

    if (data.length === 0) {
      tableBody.innerHTML = '<tr><td colspan="100%">Нет данных</td></tr>';
      return;
    }

    const columns = Object.keys(data[0]);
    
    // Создаем заголовки с обработчиками клика
    tableHeader.innerHTML = columns.map(col => 
      `<th onclick="sortTable(this)">${col}</th>`
    ).join('');

    tableBody.innerHTML = data.map(row => {
      return `<tr>${columns.map(col => `<td>${row[col]}</td>`).join('')}</tr>`;
    }).join('');

  } catch (error) {
    console.error('Ошибка загрузки данных:', error);
  }
}

function sortTable(header) {
    const table = document.getElementById("data-table"); // Исправлено: document.getElementById
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

loadTable();
