
```markdown
# Rubik’s Cube Beginner Guide — Layer 2 & Layer 3

## Notation
- **U / U'** — Up face clockwise / counter-clockwise  
- **R / R'** — Right face clockwise / counter-clockwise  
- **L / L'** — Left face clockwise / counter-clockwise  
- **F / F'** — Front face clockwise / counter-clockwise  
- **f / f'** — lowercase means turning the **front two layers** together (used in some algorithms)  

---

## Layer 2 — Middle Layer Edges

**Goal:** insert the four edge pieces into the middle layer.

**Orientation:** hold the cube so the solved face (e.g., white) is **down** and the **unsolved** face (e.g., yellow) is **up**.

### Case 1 — Insert Edge to the Right
- Align the edge piece in the **top-front** position (match its side color with the front face).
- Apply either sequence below:

```

U R U R' U'
L' U' L U

```

or the standard beginner method:

```

U R U' R' U' F' U F

```

### Case 2 — Insert Edge to the Left
- Align the edge piece in the **top-front** position.
- Apply either sequence below:

```

U' L' U L U
R U R' U'

```

or the mirrored beginner method:

```

U' L' U L U F U' F'

```

Repeat for all four middle edges.

---

## Layer 3 — Top Layer

**Goal:** make the top face one color, then permute the last layer pieces.

---

### Step A — Make the Top Cross
You will see one of:
- **Dot**
- **L-shape**
- **Line**

Use:

```

F R U R' U' F'

```

- **Dot → L → Line → Cross**: apply the same algorithm repeatedly.
- If you have an **L**, hold it in the **upper-left** of the top face.
- If you have a **Line**, hold it **horizontally**.

---

### Step B — Orient the Top Corners
Once the cross is done, orient the four top corners so all top stickers match.

```

f R U R' U' f'

```

Repeat on each mis-oriented corner:
1. Hold the corner at **front-right-up**.
2. Perform the algorithm until that corner shows the correct top color.
3. Rotate the **U** face to bring the next mis-oriented corner into place and repeat.

---

### Step C — Permute Corners & Edges

#### 1. Position Corners
If corners are in the wrong locations but the top color is correct:

```

U R U' L' U R' U' L

```

Repeat until all corners sit in their correct spots.

#### 2. Twist Individual Corners (if needed)
For each twisted corner:

```

R' D' R D

```

Repeat the sequence on that corner until correct, rotating the **U** face to bring the next twisted corner into position.

#### 3. Position Top Edges
If edges need cycling (only for some cases), use:

```

F2 U L R' F2 L' R U F2

```

*(optional if edges are already correct)*

---

## Quick Checklist
1. **Layer 2:** place each edge with the right or left insertion algorithm.  
2. **Layer 3:** form the cross (`F R U R' U' F'`).  
3. **Layer 3:** orient corners (`f R U R' U' f'`).  
4. **Layer 3:** permute corners (`U R U' L' U R' U' L`) and twist as needed (`R' D' R D`).  

---

**Tip:** Work slowly and check your cube orientation before every algorithm.  
Consistent practice will let you finish in under a minute!
```

---

