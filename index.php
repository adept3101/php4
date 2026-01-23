<?php
require_once 'checkAuth.php';
$user = getCurrentUser();

$csrf_token = $_SESSION['csrf_token'] ?? '';
?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Таблица камер</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <!-- SheetJS -->
  <script src="https://cdn.sheetjs.com/xlsx-0.19.3/package/dist/xlsx.full.min.js"></script>
  <style>
    body {
      background-color: #f8f9fa;
      padding-top: 70px; /* Увеличили отступ сверху для фиксированной панели */
    }
    
    .container {
      max-width: 1200px;
    }
    
    .card {
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      border: none;
      margin-bottom: 20px;
    }
    
    .card-header {
      background-color: #0d6efd;
      color: white;
      font-weight: 600;
    }
    
    .table-container {
      overflow-x: auto;
    }
    
    .table th {
      background-color: #f8f9fa;
      font-weight: 600;
      border-top: none;
    }
    
    .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(0, 0, 0, 0.02);
    }
    
    .form-control:focus, .form-select:focus {
      border-color: #86b7fe;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .btn-export {
      transition: all 0.3s ease;
    }
    
    .btn-export:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .table-responsive {
      border-radius: 8px;
      overflow: hidden;
    }
/* Стили для выбора цвета */
.color-option {
    cursor: pointer;
    border-radius: 4px;
    border: 1px solid #dee2e6;
    transition: transform 0.2s, box-shadow 0.2s;
}

.color-option:hover {
    transform: scale(1.1);
    box-shadow: 0 0 5px rgba(0,0,0,0.3);
}

.color-option.selected {
    border: 2px solid #0d6efd;
    box-shadow: 0 0 8px rgba(13, 110, 253, 0.5);
}

.form-control-color {
    cursor: pointer;
}

/* Индикатор выбранного цвета */
.color-preview {
    width: 30px;
    height: 30px;
    border-radius: 4px;
    display: inline-block;
    margin-right: 10px;
    border: 1px solid #dee2e6;
    vertical-align: middle;
}
    
      </style>
</head>
<a href="logout.php" class="btn btn-outline-danger btn-sm ms-3">
          <i class="bi bi-box-arrow-right me-1"></i>Выйти
        </a>
  <div class="container">
    <div class="row mb-4">
      <div class="col-12">
        <h1 class="display-6">Система управления камерами</h1>
        <p class="lead text-muted">Добро пожаловать, <?php echo htmlspecialchars($user['login']); ?>!</p>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-table me-2"></i>Список камер</span>
            <button id="btn-export" class="btn btn-success btn-sm">
              <i class="bi bi-file-earmark-excel me-1"></i>Export XLSX
            </button>
          </div>
          <div class="card-body p-0">
            <div class="table-container">
              <div class="table-responsive">
                <table class="table table-striped table-hover mb-0" id="data-table">
                  <thead class="table-light">
                    <tr id="table-header">
                    </tr>
                  </thead>
                  <tbody id="table-body">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-plus-circle me-2"></i>Добавить новую камеру
          </div>
          <div class="card-body">
            <form id="addCamera" method="POST" action="postData.php">
              <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
              <!-- <div class="mb-3"> -->
              <!--   <label for="id" class="form-label">ID</label> -->
              <!--   <input type="text" class="form-control" id="id" name="id" placeholder="Введите ID"> -->
              <!-- </div> -->
              
              <div class="mb-3">
                <label for="tittle" class="form-label">Название</label>
                <input type="text" class="form-control" id="tittle" name="tittle" placeholder="Введите название">
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <!-- <label for="size" class="form-label">Размер</label> -->
                  <!-- <input type="text" class="form-control" id="size" name="size" placeholder="Размер"> -->
<select class="form-select" id="size" name="size" required>
      <option value="" selected disabled>Размер</option>
      <option value="Small">Small</option>
      <option value="Medium">Medium</option>
      <option value="Big">Big</option>
    </select>
                </div>
                
                <!-- <div class="col-md-6 mb-3"> -->
                <!--   <label for="color" class="form-label">Цвет</label> -->
                <!--   <input type="text" class="form-control" id="color" name="color" placeholder="Цвет"> -->
                <!-- </div> -->
              </div>
              <div class="col-md-6 mb-3">
  <label for="color" class="form-label">Цвет</label>
  <div class="input-group">
    <input type="text" class="form-control" id="color" name="color" placeholder="Выберите цвет" readonly>
    <button type="button" class="btn btn-outline-secondary" id="color-picker-btn">
      <i class="bi bi-palette"></i>
    </button>
    <input type="color" id="color-input" class="form-control form-control-color" style="width: 50px; padding: 0; border: none;">
  </div>
  <div class="color-palette mt-2" id="color-palette" style="display: none;">
    <div class="d-flex flex-wrap gap-1">
      <div class="color-option" style="width: 25px; height: 25px; background-color: #ff0000;" data-color="#ff0000"></div>
      <div class="color-option" style="width: 25px; height: 25px; background-color: #00ff00;" data-color="#00ff00"></div>
      <div class="color-option" style="width: 25px; height: 25px; background-color: #0000ff;" data-color="#0000ff"></div>
      <div class="color-option" style="width: 25px; height: 25px; background-color: #ffff00;" data-color="#ffff00"></div>
      <div class="color-option" style="width: 25px; height: 25px; background-color: #ff00ff;" data-color="#ff00ff"></div>
      <div class="color-option" style="width: 25px; height: 25px; background-color: #00ffff;" data-color="#00ffff"></div>
      <div class="color-option" style="width: 25px; height: 25px; background-color: #ff9900;" data-color="#ff9900"></div>
      <div class="color-option" style="width: 25px; height: 25px; background-color: #9900ff;" data-color="#9900ff"></div>
      <div class="color-option" style="width: 25px; height: 25px; background-color: #333333;" data-color="#333333"></div>
      <div class="color-option" style="width: 25px; height: 25px; background-color: #666666;" data-color="#666666"></div>
      <div class="color-option" style="width: 25px; height: 25px; background-color: #999999;" data-color="#999999"></div>
      <div class="color-option" style="width: 25px; height: 25px; background-color: #ffffff; border: 1px solid #ccc;" data-color="#ffffff"></div>
    </div>
    <small class="text-muted d-block mt-1">Нажмите для выбора цвета</small>
  </div>
</div>
              
              <div class="mb-3">
                <label for="cost" class="form-label">Стоимость</label>
                <div class="input-group">
                  <span class="input-group-text">₽</span>
                  <input type="text" class="form-control" id="cost" name="cost" placeholder="0.00">
                </div>
              </div>
              
              <div class="mb-3">
                <label for="material_id" class="form-label">Материал</label>
                <select class="form-select" name="material_id" id="material_id" required>
                  <option value="" selected disabled>Выберите материал</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="country_id" class="form-label">Страна</label>
                <select class="form-select" name="country_id" id="country_id" required>
                  <option value="" selected disabled>Выберите страну</option>
                </select>
              </div>
              
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-send me-1"></i>Отправить
                </button>
                <button type="reset" class="btn btn-outline-secondary">
                  <i class="bi bi-x-circle me-1"></i>Очистить форму
                </button>
              </div>
            </form>
          </div>
        </div>
        
        <div class="card mt-4">
          <div class="card-header">
            <i class="bi bi-info-circle me-2"></i>Информация о сессии
          </div>
          <div class="card-body">
            <p><strong>ID пользователя:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
            <p><strong>Логин:</strong> <?php echo htmlspecialchars($user['login']); ?></p>
            <p><strong>Время входа:</strong> <?php echo date('H:i:s d.m.Y'); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="scripts/script.js"></script>
</body>

</html>
