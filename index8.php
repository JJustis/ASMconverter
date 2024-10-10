<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Simple Assembly Compiler to Binary Converter">
    <meta name="keywords" content="JavaScript, Assembly, Binary, Compiler">
    <meta name="author" content="Your Name">
    <title>Assembly Compiler with Binary Download</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #222;
            color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #333;
            border-radius: 10px;
            padding: 30px;
            width: 600px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        h1 {
            font-weight: 300;
            color: #76c7c0;
        }

        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            background: #444;
            color: #f0f0f0;
            border: 2px solid #555;
            border-radius: 5px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
        }

        button {
            margin-top: 20px;
            padding: 15px;
            border: none;
            border-radius: 5px;
            background: #76c7c0;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #60a7a0;
        }

        .output {
            margin-top: 30px;
            padding: 20px;
            background: #2c2c2e;
            border-radius: 10px;
        }

        .binary {
            font-size: 16px;
            font-family: 'Courier New', Courier, monospace;
            white-space: pre-wrap;
            background: #1e1e20;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Assembly to Binary Compiler</h1>
        <textarea id="assemblyInput" placeholder="Enter Assembly code here... (e.g., MOV A, 1)"></textarea>
        <button onclick="compileToBinary()">Compile</button>
        <button id="downloadBtn" style="display:none;" onclick="downloadBinaryFile()">Download Binary File</button>
        <div class="output">
            <h2>Binary Output</h2>
            <div id="binaryOutput" class="binary">-</div>
        </div>
    </div>

    <script>
        // Define the complete register encoding (A-Z) with 5-bit binary values
        const REGISTERS = {
            'A': '00000', 'B': '00001', 'C': '00010', 'D': '00011',
            'E': '00100', 'F': '00101', 'G': '00110', 'H': '00111',
            'I': '01000', 'J': '01001', 'K': '01010', 'L': '01011',
            'M': '01100', 'N': '01101', 'O': '01110', 'P': '01111',
            'Q': '10000', 'R': '10001', 'S': '10010', 'T': '10011',
            'U': '10100', 'V': '10101', 'W': '10110', 'X': '10111',
            'Y': '11000', 'Z': '11001'
        };

        // Define opcodes for supported instructions with 5-bit opcodes
        const OPCODES = {
            'MOV': '00001', 'ADD': '00010', 'SUB': '00011',
            'AND': '00100', 'OR': '00101', 'XOR': '00110',
            'JMP': '00111', 'CMP': '01000', 'MUL': '01001',
            'DIV': '01010', 'SHL': '01011', 'SHR': '01100'
        };

        let compiledBinary = ""; // Global variable to store compiled binary instructions

        // Compile function: Convert assembly to binary
        function compileToBinary() {
            const input = document.getElementById('assemblyInput').value;
            const lines = input.split('\n');  // Split input into individual lines
            let binaryOutput = '';

            lines.forEach(line => {
                const tokens = line.trim().split(' ');  // Tokenize each line by spaces
                if (tokens.length > 0 && OPCODES[tokens[0]]) {
                    const opcode = OPCODES[tokens[0]];  // Get the opcode
                    let operands = tokens.slice(1).join('').split(',');  // Split operands by comma
                    operands = operands.map(op => op.trim());

                    // Encode operands: could be a register or an immediate value
                    let operandBits = '';
                    operands.forEach(op => {
                        if (REGISTERS[op]) {
                            operandBits += REGISTERS[op];  // Register encoding
                        } else if (!isNaN(parseInt(op))) {
                            operandBits += parseInt(op).toString(2).padStart(5, '0');  // Immediate value to 5-bit binary
                        }
                    });

                    // Combine opcode and operand bits to form the full binary instruction (e.g., 16-bit format)
                    const instruction = (opcode + operandBits).padEnd(16, '0');  // Fill to 16 bits if needed
                    binaryOutput += instruction + '\n';
                }
            });

            // Update global variable for download
            compiledBinary = binaryOutput.trim();

            // Display the binary output in the designated div
            document.getElementById('binaryOutput').innerText = compiledBinary || '-';

            // Show the download button if there is binary data
            if (compiledBinary.length > 0) {
                document.getElementById('downloadBtn').style.display = 'block';
            }
        }

        // Function to download the compiled binary file
        function downloadBinaryFile() {
            const blob = new Blob([compiledBinary], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'compiled_binary.bin';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>
