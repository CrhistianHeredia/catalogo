/**
 * Unit tests for the printTable function in js/usuario.js
 *
 * Run with: node --experimental-vm-modules tests/printTable.test.js
 * Or: npx jest (if jest is available)
 */

// Simulate the printTable + helper functions exactly as they appear in usuario.js
// We extract just the logic, isolating it from DOM/jQuery dependencies

global.allUsuarios = [];

/**
 * Generates HTML row string from allUsuarios array (same logic as usuario.js)
 * @param {number} i - index
 * @returns {string} HTML string of a single table row
 */
function buildRow(i) {
  var btn = '<button type="button" class="btn-action btn-action-edit" onclick="editarUsu(' + i + ')" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></button>'
    + '<button type="button" class="btn-action btn-action-delete ms-1" onclick="eliminaUsu(' + i + ')" title="Eliminar"><i class="fa fa-trash" aria-hidden="true"></i></button>';
  return '<tr>'
    + '<td class="ps-4 fw-semibold">' + allUsuarios[i]['user_name'] + '</td>'
    + '<td><a href="mailto:' + allUsuarios[i]['email'] + '" class="text-decoration-none">' + allUsuarios[i]['email'] + '</a></td>'
    + '<td>' + allUsuarios[i]['phone'] + '</td>'
    + '<td class="text-center"><div class="d-flex justify-content-center gap-1">' + btn + '</div></td>'
  + '</tr>';
}

/**
 * Generates full table body HTML from global allUsuarios (same logic as printTable)
 * @returns {string}
 */
function printTableHTML() {
  var tr = "";
  for (var i = 0; i < allUsuarios.length; i++) {
    tr += buildRow(i);
  }
  return tr;
}

// ─── Tests ────────────────────────────────────────────────

function assert(condition, msg) {
  if (!condition) {
    console.error('FAIL: ' + msg);
    process.exitCode = 1;
  } else {
    console.log('PASS: ' + msg);
  }
}

// Test 1: empty users
allUsuarios = [];
var html = printTableHTML();
assert(html === '', 'Empty array produces empty string');

// Test 2: single user
allUsuarios = [
  { user_name: 'Alice', email: 'alice@test.com', phone: '555-0100', id_user: 1 }
];
html = printTableHTML();
assert(html.includes('Alice'), 'Single user: name appears');
assert(html.includes('alice@test.com'), 'Single user: email appears');
assert(html.includes('555-0100'), 'Single user: phone appears');
assert(html.includes('btn-action-edit'), 'Single user: edit button present');
assert(html.includes('btn-action-delete'), 'Single user: delete button present');
assert(html.includes('fa-pencil'), 'Single user: pencil icon');
assert(html.includes('fa-trash'), 'Single user: trash icon');
assert(html.includes('mailto:alice@test.com'), 'Single user: mailto link');
assert(html.includes('<tr>'), 'Single user: table row open');
assert(html.includes('</tr>'), 'Single user: table row close');

// Test 3: multiple users
allUsuarios = [
  { user_name: 'Alice', email: 'alice@test.com', phone: '555-0100', id_user: 1 },
  { user_name: 'Bob', email: 'bob@test.com', phone: '555-0200', id_user: 2 },
  { user_name: 'Carol', email: 'carol@test.com', phone: '555-0300', id_user: 3 }
];
html = printTableHTML();
assert(html.includes('Alice'), 'Multiple: Alice present');
assert(html.includes('Bob'), 'Multiple: Bob present');
assert(html.includes('Carol'), 'Multiple: Carol present');
assert((html.match(/<tr>/g) || []).length === 3, 'Multiple: 3 rows');
assert((html.match(/btn-action-edit/g) || []).length === 3, 'Multiple: 3 edit buttons');
assert((html.match(/btn-action-delete/g) || []).length === 3, 'Multiple: 3 delete buttons');

// Test 4: onclick references correct index
allUsuarios = [
  { user_name: 'Zero', email: 'z@t.com', phone: '000', id_user: 0 },
  { user_name: 'One', email: 'o@t.com', phone: '111', id_user: 1 }
];
html = printTableHTML();
assert(html.includes("editarUsu(0)"), 'Index 0 onclick editar');
assert(html.includes("eliminaUsu(1)"), 'Index 1 onclick elimina');
assert(!html.includes("editarUsu(2)"), 'No out-of-bounds index');

// Test 5: ps-4 class on first cell
allUsuarios = [{ user_name: 'X', email: 'x@t.com', phone: '000', id_user: 1 }];
html = printTableHTML();
assert(html.includes('ps-4'), 'First td has ps-4 class');

// Test 6: email as mailto link
assert(html.includes('href="mailto:x@t.com"'), 'Email rendered as mailto link');

console.log('\nAll tests completed.');
