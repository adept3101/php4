//
// function sortTable(header) {
//   const table = header.closest("table");
//   const tbody = table.tBodies[0];
//   const rows = Array.from(tbody.rows);
//   const columnIndex = header.cellIndex;
//   const isAscending = !tbody.dataset.sortOrder || tbody.dataset.sortOrder === "desc";
//
//
//   // Сортируем строки
//   rows.sort((a, b) => {
//     const aText = a.cells[columnIndex].textContent.trim();
//     const bText = b.cells[columnIndex].textContent.trim();
//
//
//     // Пробуем преобразовать текст в числа
//     const aValue = parseFloat(aText);
//     const bValue = parseFloat(bText);
//
//
//     // Проверяем, являются ли оба значения числами
//     const aIsNumber = !isNaN(aValue);
//     const bIsNumber = !isNaN(bValue);
//
//
//     if (aIsNumber && bIsNumber) {
//       // Оба значения - числа
//       return isAscending ? aValue - bValue : bValue - aValue;
//     } else if (aIsNumber) {
//       // Только a - число
//       return isAscending ? -1 : 1; // Число идет перед строкой
//     } else if (bIsNumber) {
//       // Только b - число
//       return isAscending ? 1 : -1; // Строка идет перед числом
//     } else {
//       // Оба значения - строки
//       return isAscending ? aText.localeCompare(bText) : bText.localeCompare(aText);
//     }
//   });
//
//
//   // Удаляем старые строки и добавляем отсортированные
//   while (tbody.firstChild) {
//     tbody.removeChild(tbody.firstChild);
//   }
//   tbody.append(...rows);
//
//
//   // Обновляем порядок сортировки
//   tbody.dataset.sortOrder = isAscending ? "asc" : "desc";
// }

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

    // Создаём заголовки
    const columns = Object.keys(data[0]);
    tableHeader.innerHTML = columns.map(col => `<th>${col}</th>`).join('');

    // Создаём строки
    tableBody.innerHTML = data.map(row => {
      return `<tr>${columns.map(col => `<td>${row[col]}</td>`).join('')}</tr>`;
    }).join('');

  } catch (error) {
    console.error('Ошибка загрузки данных:', error);
  }
}

loadTable()

// function updateUserRoles(id, updatedRoles) {
//   const data = {
//     id: id,
//     id_roles: updatedRoles
//   };
//
//   fetch('../functions/updateUserRoles.php', {
//     method: 'POST',
//     headers: {
//       'Content-Type': 'application/json'
//     },
//     body: JSON.stringify(data)
//   })
//     .then(response => response.json())
//     .then(result => {
//       // console.log(result);
//     })
//     .catch(error => {
//       console.error('Ошибка:', error);
//     });
// }
