<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Automated Salary Sheet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      @media print {
        .no-print {
          display: none;
        }
        body {
          padding: 0;
        }
      }
      [contenteditable="true"] {
        outline: none;
        transition: all 0.2s;
      }
      [contenteditable="true"]:focus {
        background-color: #fef9c3;
        border-radius: 4px;
        box-shadow: inset 0 0 4px #ccc;
      }
      #advanceModal,
      #reportModal {
        display: none;
      }
      #advanceModal.open,
      #reportModal.open {
        display: flex;
      }
    </style>
  </head>
  <body class="bg-gray-100 p-5 md:p-10">
    <div
      id="advanceModal"
      class="fixed inset-0 bg-black/60 z-50 items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-5">
          <h2 class="text-xl font-black uppercase">Advance Salary Setup</h2>
          <button
            onclick="closeModal()"
            class="text-gray-400 hover:text-black text-2xl font-bold"
          >
            &times;
          </button>
        </div>
        <div class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase"
              >Total Advance Taken (৳)</label
            ><input
              type="number"
              id="modalTotalAdvance"
              class="w-full border-2 border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-blue-500 outline-none"
            />
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase"
              >Monthly Deduction (৳)</label
            ><input
              type="number"
              id="modalMonthlyDeduct"
              class="w-full border-2 border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-blue-500 outline-none"
            />
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase"
              >Already Recovered (৳)</label
            ><input
              type="number"
              id="modalRecovered"
              class="w-full border-2 border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-blue-500 outline-none"
            />
          </div>
        </div>
        <div class="flex gap-3 mt-6">
          <button
            onclick="saveAdvance()"
            class="flex-1 bg-black text-white py-2 rounded-lg font-bold hover:bg-gray-800 text-sm"
          >
            Save & Apply
          </button>
          <button
            onclick="clearAdvance()"
            class="flex-1 bg-red-50 text-red-600 border border-red-200 py-2 rounded-lg font-bold hover:bg-red-100 text-sm"
          >
            Clear
          </button>
        </div>
      </div>
    </div>

    <div
      id="reportModal"
      class="fixed inset-0 bg-black/70 z-50 items-center justify-center p-4"
    >
      <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl p-8 relative max-h-[90vh] overflow-y-auto"
      >
        <button
          onclick="closeReport()"
          class="absolute top-4 right-4 text-gray-400 hover:text-black text-2xl no-print"
        >
          &times;
        </button>
        <div id="reportContent">
          <h2
            class="text-2xl font-black uppercase text-center border-b-2 border-black pb-2 mb-6"
          >
            Individual Detailed Salary Report
          </h2>
          <div
            id="repBasicInfo"
            class="grid grid-cols-2 gap-4 mb-6 text-sm border p-4 rounded-lg bg-gray-50"
          ></div>

          <table
            class="w-full text-[10px] border-collapse border border-gray-300 mb-6 text-center"
          >
            <thead>
              <tr class="bg-black text-white uppercase">
                <th class="border border-gray-400 p-2">Month</th>
                <th class="border border-gray-400 p-2">Total Salary</th>
                <th class="border border-gray-400 p-2">Abs. Deduct</th>
                <th class="border border-gray-400 p-2 text-orange-400">
                  Advance
                </th>
                <th class="border border-gray-400 p-2 text-orange-400">
                  Half Sal
                </th>
                <th class="border border-gray-400 p-2 bg-green-900">
                  Net Paid
                </th>
              </tr>
            </thead>
            <tbody id="historyTableBody"></tbody>
          </table>
          <div
            class="bg-green-50 p-4 border border-green-200 rounded-lg flex justify-between items-center"
          >
            <span class="font-black uppercase text-green-800"
              >Cumulative Net Paid (Total):</span
            >
            <span id="repTotalNet" class="text-xl font-black text-green-900"
              >৳0</span
            >
          </div>
        </div>
        <div class="mt-8 flex gap-4 no-print">
          <button
            onclick="window.print()"
            class="flex-1 bg-black text-white py-3 rounded-xl font-bold"
          >
            Print Report
          </button>
          <button
            onclick="closeReport()"
            class="flex-1 border-2 border-gray-200 py-3 rounded-xl font-bold"
          >
            Close
          </button>
        </div>
      </div>
    </div>

    <div class="max-w-full mx-auto bg-white p-6 shadow-2xl rounded-xl">
      <div
        class="flex justify-between items-center mb-6 border-b-2 border-black pb-4"
      >
        <div>
          <h1 class="text-4xl font-black uppercase tracking-tighter">
            Automated Salary Sheet
          </h1>
        </div>
        <div class="no-print flex gap-2">
          <input
            type="text"
            id="currentMonthName"
            placeholder="Feb 2026"
            class="border border-gray-300 px-3 py-2 rounded-lg text-sm font-bold w-40"
          />
          <button
            onclick="saveTableData()"
            class="bg-green-600 text-white px-5 py-2 rounded-lg font-bold hover:bg-green-700"
          >
            💾 Save List
          </button>
          <button
            onclick="window.print()"
            class="bg-black text-white px-5 py-2 rounded-lg font-bold hover:bg-gray-800"
          >
            Print Sheet
          </button>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table
          class="w-full border-collapse border border-gray-400 text-[11px]"
        >
          <thead>
            <tr class="bg-black text-white text-center">
              <th class="border border-gray-600 p-2">Employee ID</th>
              <th class="border border-gray-600 p-2">Employee Name</th>
              <th class="border border-gray-600 p-2">Designation</th>
              <th class="border border-gray-600 p-2">Join Date</th>
              <th class="border border-gray-600 p-2 bg-blue-900">
                Total Salary
              </th>
              <th class="border border-gray-600 p-2">Basic</th>
              <th class="border border-gray-600 p-2">Conve.</th>
              <th class="border border-gray-600 p-2">Medical</th>
              <th class="border border-gray-600 p-2">House Rent</th>
              <th class="border border-gray-600 p-2 text-red-400">Abs. Day</th>
              <th class="border border-gray-600 p-2">Abs. Deduct.</th>
              <th class="border border-gray-600 p-2 text-orange-400">
                Advance
              </th>
              <th class="border border-gray-600 p-2 text-orange-400">
                Half Sal.
              </th>
              <th class="border border-gray-600 p-2 bg-red-900">
                Total Deduct.
              </th>
              <th
                class="border border-gray-600 p-2 bg-green-800 text-yellow-400"
              >
                Net Payable
              </th>
              <th class="border border-gray-600 p-2">Sign</th>
              <th class="border border-gray-600 p-2 no-print">Action</th>
            </tr>
          </thead>
          <tbody id="tableBody"></tbody>
        </table>
        <button
          onclick="addRow()"
          class="no-print mt-4 bg-blue-600 text-white px-4 py-2 rounded font-bold"
        >
          + Add Employee Row
        </button>
      </div>
    </div>

    <script>
      let activeRow = null;

      function calculateRow(row) {
        const getV = (c) => {
          let text = row.querySelector("." + c)?.innerText || "0";
          let val = parseFloat(text.replace(/,/g, "")) || 0;
          return isNaN(val) ? 0 : val;
        };
        const setV = (c, v) => {
          if (row.querySelector("." + c))
            row.querySelector("." + c).innerText =
              Math.round(v).toLocaleString();
        };

        const total = getV("total-salary");
        setV("basic", total * 0.6);
        setV("conve", total * 0.1);
        setV("medical", total * 0.1);
        setV("rent", total * 0.2);

        const absD = Math.round((total / 30) * getV("abs-day"));
        const advTotal = parseFloat(row.dataset.advanceTotal) || 0;
        const advMonthly = parseFloat(row.dataset.advanceMonthly) || 0;
        const advRecovered = parseFloat(row.dataset.advanceRecovered) || 0;
        const currentAdv = Math.min(
          advMonthly,
          Math.max(0, advTotal - advRecovered),
        );
        const half = getV("half-sal");

        row.querySelector(".advance-display").innerText =
          Math.round(currentAdv).toLocaleString();
        setV("abs-deduct-val", absD);
        const totalD = absD + currentAdv + half;
        setV("total-deduct-val", totalD);
        setV("net-payable-val", total - totalD);
      }

      function addRow(
        name = "Name",
        desig = "Desig",
        join = "01/01/26",
        id = null,
        sal = "0",
      ) {
        const tbody = document.getElementById("tableBody");
        const rowId = id || `EMP-${Date.now().toString().slice(-4)}`;
        const row = document.createElement("tr");
        row.className = "text-center hover:bg-gray-50";
        row.dataset.advanceTotal = "0";
        row.dataset.advanceMonthly = "0";
        row.dataset.advanceRecovered = "0";

        row.innerHTML = `
            <td contenteditable="true" class="border border-gray-300 p-2 font-bold emp-id-cell">${rowId}</td>
            <td contenteditable="true" class="border border-gray-300 p-2">${name}</td>
            <td contenteditable="true" class="border border-gray-300 p-2 italic text-gray-500">${desig}</td>
            <td contenteditable="true" class="border border-gray-300 p-2">${join}</td>
            <td contenteditable="true" class="border border-gray-300 p-2 font-bold text-blue-700 calc-input total-salary">${sal}</td>
            <td class="border border-gray-300 p-2 basic">0</td>
            <td class="border border-gray-300 p-2 conve">0</td>
            <td class="border border-gray-300 p-2 medical">0</td>
            <td class="border border-gray-300 p-2 rent">0</td>
            <td contenteditable="true" class="border border-gray-300 p-2 text-red-600 font-bold calc-input abs-day">0</td>
            <td class="border border-gray-300 p-2 bg-gray-50 abs-deduct-val font-semibold">0</td>
            <td class="border border-gray-300 p-2 text-orange-600">
                <div class="flex items-center justify-center gap-1">
                    <span class="advance-display font-bold">0</span>
                    <button onclick="openModal(this.closest('tr'))" class="no-print font-black text-xs hover:text-black text-orange-400">+</button>
                </div>
            </td>
            <td contenteditable="true" class="border border-gray-300 p-2 text-orange-600 calc-input half-sal">0</td>
            <td class="border border-gray-300 p-2 bg-red-50 text-red-700 font-black total-deduct-val">0</td>
            <td class="border border-gray-300 p-2 bg-green-50 text-green-800 font-black net-payable-val">0</td>
            <td class="border border-gray-300 p-2"><div class="h-4 border-b border-gray-400"></div></td>
            <td class="border border-gray-300 p-2 no-print space-x-1">
                <button onclick="generateReport(this.closest('tr'))" class="bg-blue-600 text-white px-1 py-0.5 rounded text-[9px] uppercase">Rep</button>
                <button onclick="this.closest('tr').remove()" class="text-red-500 font-bold">✕</button>
            </td>
        `;
        tbody.appendChild(row);
        row.querySelectorAll(".calc-input").forEach((cell) => {
          cell.addEventListener("input", () => calculateRow(row));
          cell.addEventListener("blur", () => {
            if (cell.innerText.trim() === "") cell.innerText = "0";
            calculateRow(row);
          });
        });
        calculateRow(row);
        return row;
      }

      function saveTableData() {
        const rows = [];
        const month =
          document.getElementById("currentMonthName").value ||
          new Date().toLocaleDateString("en-GB", {
            month: "short",
            year: "numeric",
          });
        const history = JSON.parse(
          localStorage.getItem("salaryHistory") || "{}",
        );

        document.querySelectorAll("#tableBody tr").forEach((row) => {
          const empId = row.querySelector(".emp-id-cell").innerText.trim();
          const data = {
            id: empId,
            name: row.cells[1].innerText,
            desig: row.cells[2].innerText,
            join: row.cells[3].innerText,
            totalSalary: row.querySelector(".total-salary").innerText,
            absDeduct: row.querySelector(".abs-deduct-val").innerText,
            advancePaid: row.querySelector(".advance-display").innerText,
            halfSal: row.querySelector(".half-sal").innerText,
            netPayable: row.querySelector(".net-payable-val").innerText,
            advTotal: row.dataset.advanceTotal,
            advMonthly: row.dataset.advanceMonthly,
            advRecovered: row.dataset.advanceRecovered,
          };
          rows.push(data);

          if (!history[empId]) history[empId] = [];
          const idx = history[empId].findIndex((h) => h.month === month);
          if (idx > -1) history[empId][idx] = { month, ...data };
          else history[empId].push({ month, ...data });
        });

        localStorage.setItem("salaryListData", JSON.stringify(rows));
        localStorage.setItem("salaryHistory", JSON.stringify(history));
        alert("Saved & History Updated for " + month);
      }

      function loadTableData() {
        const data = JSON.parse(localStorage.getItem("salaryListData") || "[]");
        if (data.length === 0) addRow();
        else
          data.forEach((d) => {
            const row = addRow(d.name, d.desig, d.join, d.id, d.totalSalary);
            row.dataset.advanceTotal = d.advTotal;
            row.dataset.advanceMonthly = d.advMonthly;
            row.dataset.advanceRecovered = d.advRecovered;
            calculateRow(row);
          });
      }

      function generateReport(row) {
        const id = row.querySelector(".emp-id-cell").innerText.trim();
        const historyData = JSON.parse(
          localStorage.getItem("salaryHistory") || "{}",
        );
        const empHistory = historyData[id] || [];

        document.getElementById("repBasicInfo").innerHTML = `
            <div><strong>Name:</strong> ${row.cells[1].innerText}</div>
            <div><strong>ID:</strong> ${id}</div>
            <div><strong>Designation:</strong> ${row.cells[2].innerText}</div>
        `;

        const hBody = document.getElementById("historyTableBody");
        hBody.innerHTML = "";
        let grandTotal = 0;

        if (empHistory.length === 0) {
          hBody.innerHTML = `<tr><td colspan="6" class="p-4 text-gray-500">No history found. Please click "Save List" first.</td></tr>`;
        } else {
          empHistory.forEach((h) => {
            const net = parseFloat(h.netPayable.replace(/,/g, "")) || 0;
            grandTotal += net;
            hBody.innerHTML += `<tr class="border-b italic">
                    <td class="border p-2 font-bold text-black">${h.month}</td>
                    <td class="border p-2">৳${h.totalSalary}</td>
                    <td class="border p-2">৳${h.absDeduct}</td>
                    <td class="border p-2">৳${h.advancePaid}</td>
                    <td class="border p-2">৳${h.halfSal}</td>
                    <td class="border p-2 font-black text-green-900">৳${h.netPayable}</td>
                </tr>`;
          });
        }

        document.getElementById("repTotalNet").innerText =
          "৳" + grandTotal.toLocaleString();
        document.getElementById("reportModal").classList.add("open");
      }

      function openModal(row) {
        activeRow = row;
        document.getElementById("modalTotalAdvance").value =
          row.dataset.advanceTotal;
        document.getElementById("modalMonthlyDeduct").value =
          row.dataset.advanceMonthly;
        document.getElementById("modalRecovered").value =
          row.dataset.advanceRecovered;
        document.getElementById("advanceModal").classList.add("open");
      }
      function closeModal() {
        document.getElementById("advanceModal").classList.remove("open");
      }
      function closeReport() {
        document.getElementById("reportModal").classList.remove("open");
      }
      function saveAdvance() {
        activeRow.dataset.advanceTotal =
          document.getElementById("modalTotalAdvance").value || 0;
        activeRow.dataset.advanceMonthly =
          document.getElementById("modalMonthlyDeduct").value || 0;
        activeRow.dataset.advanceRecovered =
          document.getElementById("modalRecovered").value || 0;
        calculateRow(activeRow);
        closeModal();
      }

      window.onload = loadTableData;
    </script>
  </body>
</html>
