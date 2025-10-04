<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Таблица БД</title>
    <style>
    </style>
</head>
<body>
    <div class="container">
        <h1>Просмотр таблицы базы данных</h1>
        
        <div class="controls">
            <select id="tableSelect">
                <option value="">Выберите таблицу</option>
            </select>
            
        </div>
        
        <div id="errorMessage" class="error" style="display: none;"></div>
        
        <div id="tableContainer">
            <div class="loading">Выберите таблицу для отображения данных</div>
        </div>
        
        <div class="pagination" id="pagination" style="display: none;">
            <button onclick="changePage(-1)">Назад</button>
            <span id="pageInfo">Страница 1 из 1</span>
            <button onclick="changePage(1)">Вперед</button>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let currentTable = '';
        const rowsPerPage = 10;

        async function loadTableData(tableName, page = 1, search = '') {
            const tableContainer = document.getElementById('tableContainer');
            const errorMessage = document.getElementById('errorMessage');
            
            tableContainer.innerHTML = '<div class="loading">Загрузка данных...</div>';
            errorMessage.style.display = 'none';
            
            try {
                // const response = await fetch(`/api/table/${tableName}?page=${page}&search=${search}`);
                // const data = await response.json();
                
                const data = generateDemoData(tableName, page, search);
                
                if (data.error) {
                    throw new Error(data.error);
                }
                
                displayTable(data.rows, data.columns);
                updatePagination(data.totalPages, page);
                currentTable = tableName;
                
            } catch (error) {
                errorMessage.textContent = 'Ошибка загрузки данных: ' + error.message;
                errorMessage.style.display = 'block';
                tableContainer.innerHTML = '';
                pagination.style.display = 'none';
            }
        }

        function displayTable(rows, columns) {
            const tableContainer = document.getElementById('tableContainer');
            
            if (rows.length === 0) {
                tableContainer.innerHTML = '<div class="loading">Нет данных для отображения</div>';
                return;
            }
            
            let html = '<table>';
            
            html += '<thead><tr>';
            columns.forEach(column => {
                html += `<th>${column}</th>`;
            });
            html += '</tr></thead>';
            
            html += '<tbody>';
            rows.forEach(row => {
                html += '<tr>';
                columns.forEach(column => {
                    html += `<td>${row[column] || ''}</td>`;
                });
                html += '</tr>';
            });
            html += '</tbody></table>';
            
            tableContainer.innerHTML = html;
        }

        function updatePagination(totalPages, currentPage) {
            const pagination = document.getElementById('pagination');
            const pageInfo = document.getElementById('pageInfo');
            
            if (totalPages <= 1) {
                pagination.style.display = 'none';
                return;
            }
            
            pageInfo.textContent = `Страница ${currentPage} из ${totalPages}`;
            pagination.style.display = 'flex';
        }

        function changePage(direction) {
            currentPage += direction;
            loadTableData(currentTable, currentPage, document.getElementById('searchInput').value);
        }

        function searchTable() {
            currentPage = 1;
            const searchValue = document.getElementById('searchInput').value;
            loadTableData(currentTable, currentPage, searchValue);
        }

        function refreshTable() {
            loadTableData(currentTable, currentPage, document.getElementById('searchInput').value);
        }


        document.addEventListener('DOMContentLoaded', function() {
            const tableSelect = document.getElementById('tableSelect');
            
            tableSelect.addEventListener('change', function() {
                if (this.value) {
                    currentPage = 1;
                    loadTableData(this.value);
                }
            });
            
            document.getElementById('searchInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchTable();
                }
            });
        });

        function generateDemoData(tableName, page, search) {
            return new Promise(resolve => {
                setTimeout(() => {
                    let data = {
                        columns: [],
                        rows: [],
                        totalPages: 1
                    };
                    
                    switch(tableName) {
                        case 'users':
                            data.columns = ['id', 'name', 'email', 'role', 'created_at'];
                            for (let i = 1; i <= 25; i++) {
                                data.rows.push({
                                    id: i,
                                    name: `Пользователь ${i}`,
                                    email: `user${i}@example.com`,
                                    role: i % 3 === 0 ? 'admin' : 'user',
                                    created_at: new Date().toISOString().split('T')[0]
                                });
                            }
                            break;
                        case 'products':
                            data.columns = ['id', 'name', 'price', 'category', 'stock'];
                            for (let i = 1; i <= 20; i++) {
                                data.rows.push({
                                    id: i,
                                    name: `Товар ${i}`,
                                    price: (Math.random() * 1000).toFixed(2),
                                    category: ['Электроника', 'Одежда', 'Книги'][i % 3],
                                    stock: Math.floor(Math.random() * 100)
                                });
                            }
                            break;
                        case 'orders':
                            data.columns = ['id', 'user_id', 'amount', 'status', 'order_date'];
                            for (let i = 1; i <= 15; i++) {
                                data.rows.push({
                                    id: i,
                                    user_id: Math.floor(Math.random() * 10) + 1,
                                    amount: (Math.random() * 500).toFixed(2),
                                    status: ['pending', 'completed', 'cancelled'][i % 3],
                                    order_date: new Date(Date.now() - Math.random() * 10000000000).toISOString().split('T')[0]
                                });
                            }
                            break;
                    }
                    
                    if (search) {
                        data.rows = data.rows.filter(row => 
                            Object.values(row).some(value => 
                                value.toString().toLowerCase().includes(search.toLowerCase())
                            )
                        );
                    }
                    
                    const startIndex = (page - 1) * rowsPerPage;
                    const endIndex = startIndex + rowsPerPage;
                    data.totalPages = Math.ceil(data.rows.length / rowsPerPage);
                    data.rows = data.rows.slice(startIndex, endIndex);
                    
                    resolve(data);
                }, 500);
            });
        }
    </script>
</body>
</html>
